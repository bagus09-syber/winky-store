<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditLogService;
use App\Services\InventoryService;
use App\Services\SellerSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected InventoryService $inventoryService;
    protected SellerSettlementService $settlementService;

    public function __construct(InventoryService $inventoryService, SellerSettlementService $settlementService)
    {
        $this->inventoryService = $inventoryService;
        $this->settlementService = $settlementService;
    }

    public function index(Request $request)
    {
        $query = Order::with('user', 'payment', 'store');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'address', 'store']);

        $validTransitions = Order::getValidTransitions();

        return view('admin.orders.show', compact('order', 'validTransitions'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validTransitions = Order::getValidTransitions();
        $allowedStatuses = $validTransitions[$order->status] ?? [];

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_merge($allowedStatuses, [$order->status])),
        ]);

        $newStatus = $validated['status'];

        if ($newStatus === $order->status) {
            return back()->with('info', 'Status sudah benar.');
        }

        if (!$order->canTransitionTo($newStatus)) {
            return back()->withErrors(['status' => 'Transisi status tidak valid.']);
        }

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;
            $order->update(['status' => $newStatus, 'seller_status' => $newStatus]);

            if ($newStatus === 'paid' && $order->payment && $order->payment->status === 'pending') {
                $order->payment->update(['status' => 'paid']);
                $this->settlementService->settleOrder($order);
            }

            if ($newStatus === 'delivered') {
                $order->update(['delivered_at' => now()]);
            }

            if ($newStatus === 'completed') {
                $this->settlementService->completeOrder($order);
            }

            if ($newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $this->inventoryService->addStock(
                            $product,
                            $item->quantity,
                            'return',
                            "Pesanan #{$order->order_number} dibatalkan",
                            null,
                            'App\\Models\\Order',
                            $order->id,
                            $item->store_id
                        );
                    }
                }

                if ($order->seller_earning > 0) {
                    $this->settlementService->refundOrder($order, $order->seller_earning, 'Pesanan dibatalkan');
                }
            }

            $notificationTypes = [
                'processing' => 'order_processing',
                'shipped' => 'order_shipped',
                'delivered' => 'order_delivered',
                'cancelled' => 'order_cancelled',
            ];

            if (isset($notificationTypes[$newStatus])) {
                $labels = [
                    'processing' => 'sedang diproses',
                    'shipped' => 'sudah dikirim',
                    'delivered' => 'sudah sampai',
                    'cancelled' => 'dibatalkan',
                ];

                \App\Models\InAppNotification::create([
                    'user_id' => $order->user_id,
                    'type' => $notificationTypes[$newStatus],
                    'data' => [
                        'title' => 'Status Pesanan Diubah',
                        'message' => "Pesanan #{$order->order_number} {$labels[$newStatus]}.",
                        'url' => route('orders.show', $order->order_number),
                    ],
                ]);
            }

            AuditLogService::log(
                "order.status_changed",
                get_class($order),
                $order->id,
                ['status' => $oldStatus],
                ['status' => $newStatus],
                $request
            );

            DB::commit();

            return redirect()->route('admin.orders.show', $order->order_number)
                ->with('success', 'Status pesanan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['status' => 'Gagal memperbarui status: ' . $e->getMessage()]);
        }
    }

    public function shipOrder(Request $request, Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->withErrors(['tracking_number' => 'Pesanan harus dalam status "Diproses" untuk dikirim.']);
        }

        $validated = $request->validate([
            'tracking_number' => 'required|string|max:50',
            'shipping_courier' => 'nullable|string|max:50',
            'shipping_service' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'tracking_number' => $validated['tracking_number'],
                'shipped_at' => now(),
                'status' => 'shipped',
                'seller_status' => 'shipped',
            ];

            if (!empty($validated['shipping_courier'])) {
                $updateData['shipping_courier'] = $validated['shipping_courier'];
            }
            if (!empty($validated['shipping_service'])) {
                $updateData['shipping_service'] = $validated['shipping_service'];
            }

            $order->update($updateData);

            \App\Models\InAppNotification::create([
                'user_id' => $order->user_id,
                'type' => 'order_shipped',
                'data' => [
                    'title' => 'Pesanan Dikirim',
                    'message' => "Pesanan #{$order->order_number} sudah dikirim. Resi: {$validated['tracking_number']}",
                    'url' => route('orders.show', $order->order_number),
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['tracking_number' => 'Gagal mengirim pesanan: ' . $e->getMessage()]);
        }
    }
}
