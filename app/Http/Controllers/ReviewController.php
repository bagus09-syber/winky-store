<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\ReviewHelpfulVote;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:2048',
        ]);

        $product = \App\Models\Product::find($validated['product_id']);

        if (!$product->canBeReviewedBy($user)) {
            return back()->withErrors(['rating' => 'Anda belum bisa memberi ulasan untuk produk ini.']);
        }

        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return back()->errors()->addAll(['rating' => 'Anda sudah memberi ulasan untuk produk ini.']);
        }

        $isVerified = false;
        if (!empty($validated['order_id'])) {
            $order = Order::where('id', $validated['order_id'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['delivered', 'completed'])
                ->whereHas('items', fn($q) => $q->where('product_id', $validated['product_id']))
                ->first();
            $isVerified = (bool) $order;
        }

        DB::beginTransaction();

        try {
            $review = Review::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'order_id' => $validated['order_id'] ?? null,
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'comment' => $validated['comment'],
                'is_verified_purchase' => $isVerified,
                'is_approved' => true,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $review->images()->create(['path' => $path]);
                }
            }

            DB::commit();

            return back()->with('success', 'Ulasan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['rating' => 'Gagal mengirim ulasan.']);
        }
    }

    public function toggleHelpful(Review $review)
    {
        $user = Auth::user();

        $existing = ReviewHelpfulVote::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $review->decrement('helpful_count');
        } else {
            ReviewHelpfulVote::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
            ]);
            $review->increment('helpful_count');
        }

        return back();
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
