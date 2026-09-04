<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('comment', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                  ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        if ($request->has('verified')) {
            $query->where('is_verified_purchase', $request->boolean('verified'));
        }

        if ($request->has('approved')) {
            $query->where('is_approved', $request->boolean('approved'));
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review ' . ($review->is_approved ? 'disetujui' : 'disembunyikan') . '.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}
