<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\Product;
use App\Services\AI\AIService;
use App\Services\AI\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AIController extends Controller
{
    protected AIService $ai;
    protected RecommendationService $recommendations;

    public function __construct(AIService $ai, RecommendationService $recommendations)
    {
        $this->ai = $ai;
        $this->recommendations = $recommendations;
    }

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'nullable|integer|exists:ai_conversations,id',
            'product_id' => 'nullable|integer|exists:products,id',
        ]);

        $message = $validated['message'];
        $context = [];

        if (!empty($validated['product_id'])) {
            $product = Product::find($validated['product_id']);
            $context['product'] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'stock' => $product->stock,
                'category' => $product->category->name ?? '',
                'brand' => $product->brand->name ?? '',
                'description' => $product->description ?? '',
            ];
        }

        if (auth()->check()) {
            $context['user_id'] = auth()->id();
            $context['exclude_ids'] = $this->recommendations->getRecentlyViewed(5);
        } else {
            $context['session_id'] = Session::getId();
        }

        $conversation = $this->getOrCreateConversation(
            $validated['conversation_id'] ?? null,
            $message
        );

        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

        $response = $this->ai->chat($message, $context);

        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $response['message'],
            'metadata' => [
                'type' => $response['type'] ?? 'text',
                'products' => $response['products'] ?? null,
                'product' => $response['product'] ?? null,
            ],
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message' => $response['message'],
            'type' => $response['type'] ?? 'text',
            'products' => $response['products'] ?? null,
            'product' => $response['product'] ?? null,
        ]);
    }

    public function getConversations(Request $request)
    {
        $query = AiConversation::with('latestMessage');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', Session::getId());
        }

        $conversations = $query->latest()->take(20)->get();

        return response()->json([
            'success' => true,
            'conversations' => $conversations->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'last_message' => $c->latestMessage->content ?? null,
                'created_at' => $c->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function getConversation(Request $request, int $id)
    {
        $conversation = AiConversation::find($id);

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversation not found'], 404);
        }

        if (auth()->check() && $conversation->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!auth()->check() && $conversation->session_id !== Session::getId()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
            ],
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'role' => $m->role,
                'content' => $m->content,
                'metadata' => $m->metadata,
                'created_at' => $m->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function deleteConversation(Request $request, int $id): JsonResponse
    {
        $conversation = AiConversation::find($id);

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        if (auth()->check() && $conversation->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $conversation->messages()->delete();
        $conversation->delete();

        return response()->json(['success' => true, 'message' => 'Conversation deleted']);
    }

    public function searchProducts(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $shoppingService = app(\App\Services\AI\AIShoppingService::class);
        $results = $shoppingService->smartSearch($validated['query']);

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    public function getSuggestions(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $shoppingService = app(\App\Services\AI\AIShoppingService::class);
        $suggestions = $shoppingService->getSuggestions($validated['q']);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }

    public function getRecommendations(Request $request)
    {
        $type = $request->get('type', 'trending');
        $limit = min((int) $request->get('limit', 10), 20);

        $products = match ($type) {
            'recently_viewed' => $this->recommendations->getRecentlyViewed($limit),
            'trending' => $this->recommendations->getTrendingProducts($limit),
            'popular' => $this->recommendations->getPopularProducts($limit),
            'for_you' => auth()->check()
                ? $this->recommendations->getRecommendedForUser(auth()->id(), $limit)
                : $this->recommendations->getTrendingProducts($limit),
            default => $this->recommendations->getTrendingProducts($limit),
        };

        return response()->json([
            'success' => true,
            'type' => $type,
            'products' => $products,
        ]);
    }

    public function getSimilarProducts(Request $request, int $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $products = $this->recommendations->getSimilarProducts($product);

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function getFrequentlyBoughtTogether(Request $request, int $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $products = $this->recommendations->getFrequentlyBoughtTogether($product);

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    private function getOrCreateConversation(?int $id, string $firstMessage): AiConversation
    {
        if ($id) {
            return AiConversation::findOrFail($id);
        }

        $title = mb_substr($firstMessage, 0, 50);
        if (mb_strlen($firstMessage) > 50) $title .= '...';

        $conversation = AiConversation::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : Session::getId(),
            'title' => $title,
        ]);

        return $conversation;
    }
}
