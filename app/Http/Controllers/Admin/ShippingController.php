<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $warehouse = Warehouse::getActive();
        $methods = ShippingMethod::orderBy('courier')->orderBy('service')->get();
        $driver = config('shipping.driver', 'development');

        return view('admin.shipping.index', compact('warehouse', 'methods', 'driver'));
    }

    public function updateWarehouse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'full_address' => 'required|string',
        ]);

        $warehouse = Warehouse::getActive();

        if ($warehouse) {
            $warehouse->update($validated);
        } else {
            Warehouse::create(array_merge($validated, ['is_active' => true]));
        }

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Warehouse/Origin berhasil diperbarui.');
    }

    public function storeMethod(Request $request)
    {
        $validated = $request->validate([
            'courier' => 'required|string|max:50',
            'service' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'estimated_days' => 'required|integer|min:1|max:30',
            'base_price' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'required|integer|min:1',
        ]);

        ShippingMethod::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil ditambahkan.');
    }

    public function updateMethod(Request $request, ShippingMethod $method)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'estimated_days' => 'required|integer|min:1|max:30',
            'base_price' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'required|integer|min:1',
        ]);

        $method->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil diperbarui.');
    }

    public function toggleMethod(ShippingMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);

        return redirect()->route('admin.shipping.index')
            ->with('success', $method->courier . ' ' . $method->service . ($method->is_active ? ' diaktifkan' : ' dinonaktifkan') . '.');
    }

    public function destroyMethod(ShippingMethod $method)
    {
        $method->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil dihapus.');
    }
}
