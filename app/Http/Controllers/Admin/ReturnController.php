<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['user', 'order']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reason', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                  ->orWhereHas('order', fn($q) => $q->where('order_number', 'like', "%{$request->search}%"));
            });
        }

        $returns = $query->latest()->paginate(15)->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    public function show(ReturnRequest $return)
    {
        $return->load(['user', 'order.items.product']);

        return view('admin.returns.show', compact('return'));
    }

    public function update(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'status' => 'required|in:requested,approved,rejected,received,refunded',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $return->update($validated);

        return redirect()->route('admin.returns.show', $return->id)
            ->with('success', 'Return request berhasil diperbarui.');
    }
}
