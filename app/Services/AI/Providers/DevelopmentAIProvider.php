<?php

namespace App\Services\AI\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Services\AI\AIProviderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DevelopmentAIProvider implements AIProviderInterface
{
    private array $greetings = [
        'halo', 'hai', 'hi', 'hello', 'hey', 'selamat pagi', 'selamat siang',
        'selamat sore', 'selamat malam', 'pagi', 'siang', 'sore', 'malam',
    ];

    private array $budgetKeywords = [
        'murah', 'budget', 'hemat', 'terjangkau', 'diskon', 'promo',
        'dibawah', 'di bawah', 'bawah', 'sampai', 'maksimal', 'max',
        'harga', 'rp', 'ribu', 'juta',
    ];

    public function chat(string $message, array $context = []): array
    {
        $lower = mb_strtolower(trim($message));

        if (in_array($lower, $this->greetings)) {
            return $this->greetingResponse();
        }

        if (Str::containsMany($lower, ['terima kasih', 'makasih', 'thanks', 'thx'])) {
            return [
                'message' => "Sama-sama! 😊 Ada lagi yang bisa saya bantu?",
                'type' => 'text',
            ];
        }

        if (Str::containsMany($lower, ['populer', 'trending', 'laris', 'best seller', 'terlaris'])) {
            return $this->trendingResponse();
        }

        if (Str::containsMany($lower, ['rekomendasi', 'recommend', 'saran', 'suggestion', 'bagus'])) {
            return $this->recommendationResponse($context);
        }

        if (Str::containsMany($lower, ['bandingkan', 'compare', 'perbandingan', 'vs'])) {
            return $this->compareResponse($lower);
        }

        if (Str::containsMany($lower, ['stok', 'stock', 'tersedia', 'habis', 'kosong'])) {
            return $this->stockCheckResponse($lower);
        }

        if (Str::containsMany($lower, ['pengiriman', 'shipping', 'kirim', 'ekspedisi'])) {
            return [
                'message' => "Kami mendukung pengiriman ke seluruh Indonesia! 🚚\n\nUntuk cek ongkir, pilih produk → Checkout → masukkan alamat tujuan. Sistem akan menampilkan opsi pengiriman yang tersedia.",
                'type' => 'text',
            ];
        }

        if (Str::containsMany($lower, ['pembayaran', 'payment', 'bayar', 'transfer', 'cod'])) {
            return [
                'message' => "Metode pembayaran yang tersedia: 💳\n\n• QRIS (Scan QR)\n• Transfer Bank (BCA, BNI, Mandiri)\n• E-Wallet (GoPay, OVO, Dana)\n\nSemua pembayaran diproses secara aman melalui sistem kami.",
                'type' => 'text',
            ];
        }

        if (Str::containsMany($lower, ['retur', 'return', 'refund', 'kembali'])) {
            return [
                'message' => "Kebijakan retur WINKY STORE: 📦\n\n• Retur dalam 7 hari setelah diterima\n• Produk harus dalam kondisi asli\n• Dana dikembalikan dalam 3-5 hari kerja\n\nAjukan retur melalui menu Pesanan → Detail Pesanan → Ajukan Retur.",
                'type' => 'text',
            ];
        }

        if (Str::containsMany($lower, ['seller', 'toko', 'jual', 'menjadi seller'])) {
            return [
                'message' => "Tertarik menjadi seller? 🏪\n\nDaftar Seller Center:\n1. Klik 'Seller Center' di navbar\n2. Isi data toko Anda\n3. Tunggu verifikasi admin\n4. Mulai jual!\n\nKomisi marketplace hanya 5% per transaksi.",
                'type' => 'text',
            ];
        }

        $intent = $this->parseSearchIntent($message);

        if (!empty($intent['products']) || !empty($intent['keywords'])) {
            return $this->searchProductResponse($intent);
        }

        return [
            'message' => "Maaf, saya belum bisa memahami pertanyaan Anda. 🤔\n\nCoba tanyakan:\n• Cari produk tertentu\n• Cek harga produk\n• Rekomendasi produk\n• Stok produk\n• Pengiriman & Pembayaran",
            'type' => 'text',
        ];
    }

    public function analyzeProduct(array $productData): array
    {
        $strengths = [];
        $weaknesses = [];
        $suggestions = [];

        if (($productData['average_rating'] ?? 0) >= 4.5) {
            $strengths[] = 'Rating tinggi';
        } elseif (($productData['average_rating'] ?? 0) < 3.0) {
            $weaknesses[] = 'Rating rendah';
            $suggestions[] = 'Tingkatkan kualitas produk dan layanan';
        }

        if (($productData['reviews_count'] ?? 0) > 50) {
            $strengths[] = 'Banyak ulasan';
        } elseif (($productData['reviews_count'] ?? 0) < 5) {
            $suggestions[] = 'Ajak pembeli untuk memberikan ulasan';
        }

        if (($productData['stock'] ?? 0) <= 0) {
            $weaknesses[] = 'Stok habis';
            $suggestions[] = 'Segera restok produk ini';
        } elseif (($productData['stock'] ?? 0) <= 5) {
            $weaknesses[] = 'Stok hampir habis';
            $suggestions[] = 'Restok sebelum kehabisan';
        }

        if (empty($productData['description']) || strlen($productData['description'] ?? '') < 50) {
            $suggestions[] = 'Tambah deskripsi yang lebih detail';
        }

        if (empty($productData['sale_price']) && ($productData['stock'] ?? 0) > 20) {
            $suggestions[] = 'Pertimbangkan untuk memberikan diskon';
        }

        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'suggestions' => $suggestions,
            'score' => $this->calculateProductScore($productData),
        ];
    }

    public function generateProductContent(array $keywords, string $type = 'description'): string
    {
        $keywordStr = implode(' ', $keywords);

        if ($type === 'title') {
            $templates = [
                "{$keywordStr} Premium Quality",
                "{$keywordStr} Original Terbaru",
                "{$keywordStr} Best Seller",
                "{$keywordStr} Limited Edition",
                "{$keywordStr} Pro Series",
            ];
            return $templates[array_rand($templates)];
        }

        if ($type === 'seo_title') {
            return "Beli {$keywordStr} Terbaik - Harga Murah | WINKY STORE";
        }

        if ($type === 'meta_description') {
            return "Beli {$keywordStr} dengan harga terbaik di WINKY STORE. Garansi resmi, pengiriman cepat, dan layanan terpercaya. Belanja sekarang!";
        }

        $templates = [
            "Produk {$keywordStr} ini hadir dengan kualitas premium yang tidak perlu diragukan lagi. Didesain khusus untuk memberikan pengalaman terbaik bagi penggunanya.",
            "Dengan fitur unggulan dan desain modern, {$keywordStr} menjadi pilihan tepat untuk kebutuhan Anda. Terbuat dari bahan berkualitas tinggi.",
            "{$keywordStr} merupakan produk pilihan yang sudah terbukti kualitasnya. Cocok untuk penggunaan sehari-hari maupun kebutuhan profesional.",
        ];

        return $templates[array_rand($templates)];
    }

    public function recommendProducts(array $userContext, int $limit = 10): array
    {
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with(['category', 'brand', 'store']);

        if (!empty($userContext['category_id'])) {
            $query->where('category_id', $userContext['category_id']);
        }

        if (!empty($userContext['brand_id'])) {
            $query->where('brand_id', $userContext['brand_id']);
        }

        if (!empty($userContext['min_price'])) {
            $query->where('price', '>=', $userContext['min_price']);
        }

        if (!empty($userContext['max_price'])) {
            $query->where('price', '<=', $userContext['max_price']);
        }

        if (!empty($userContext['exclude_ids'])) {
            $query->whereNotIn('id', $userContext['exclude_ids']);
        }

        $products = $query->orderByRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id AND is_approved = 1) DESC NULLS LAST')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'effective_price' => $product->getEffectivePrice(),
                    'image' => $product->image,
                    'average_rating' => $product->average_rating,
                    'reviews_count' => $product->reviews_count,
                    'brand' => $product->brand->name ?? '-',
                    'category' => $product->category->name ?? '-',
                ];
            })
            ->toArray();

        return $products;
    }

    public function analyzeSellerInsights(array $sellerData): array
    {
        $insights = [];

        if (($sellerData['revenue_growth'] ?? 0) > 10) {
            $insights[] = [
                'type' => 'positive',
                'title' => 'Pendapatan Meningkat',
                'message' => 'Pendapatan minggu ini naik ' . number_format($sellerData['revenue_growth'], 1) . '% dari minggu lalu.',
            ];
        } elseif (($sellerData['revenue_growth'] ?? 0) < -10) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Pendapatan Menurun',
                'message' => 'Pendapatan minggu ini turun ' . number_format(abs($sellerData['revenue_growth']), 1) . '% dari minggu lalu.',
            ];
        }

        if (($sellerData['low_stock_count'] ?? 0) > 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Stok Menipis',
                'message' => "{$sellerData['low_stock_count']} produk memiliki stok di bawah ambang batas.",
            ];
        }

        if (($sellerData['pending_orders'] ?? 0) > 5) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Pesanan Pending',
                'message' => "{$sellerData['pending_orders']} pesanan menunggu diproses.",
            ];
        }

        if (($sellerData['conversion_rate'] ?? 0) < 2) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Conversion Rate Rendah',
                'message' => 'Tingkat konversi (' . number_format($sellerData['conversion_rate'], 1) . '%) bisa ditingkatkan.',
            ];
        }

        return $insights;
    }

    public function parseSearchIntent(string $query): array
    {
        $intent = [
            'keywords' => [],
            'category' => null,
            'brand' => null,
            'min_price' => null,
            'max_price' => null,
            'features' => [],
            'products' => [],
        ];

        $lower = mb_strtolower($query);

        preg_match_all('/\d[\d.]*\s*(ribu|juta|k|jt)/i', $lower, $priceMatches);
        $budgetValues = [];
        foreach ($priceMatches[0] as $match) {
            $num = (float) str_replace('.', '', preg_replace('/[^0-9.]/', '', $match));
            if (stripos($match, 'juta') !== false || stripos($match, 'jt') !== false) {
                $num *= 1000000;
            } elseif (stripos($match, 'ribu') !== false || stripos($match, 'k') !== false) {
                $num *= 1000;
            }
            $budgetValues[] = $num;
        }

        if (count($budgetValues) >= 2) {
            $intent['min_price'] = min($budgetValues);
            $intent['max_price'] = max($budgetValues);
        } elseif (count($budgetValues) === 1) {
            if (preg_match('/(di\s*bawah|bawah|maksimal|max|sampai|under|<)/i', $lower)) {
                $intent['max_price'] = $budgetValues[0];
            } elseif (preg_match('/(di\s*atas|atas|minimal|min|over|>)/i', $lower)) {
                $intent['min_price'] = $budgetValues[0];
            } else {
                $intent['max_price'] = $budgetValues[0] * 1.2;
                $intent['min_price'] = $budgetValues[0] * 0.5;
            }
        }

        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $cat) {
            if (Str::contains($lower, mb_strtolower($cat->name))) {
                $intent['category'] = $cat->id;
                break;
            }
            if ($cat->children) {
                foreach ($cat->children as $child) {
                    if (Str::contains($lower, mb_strtolower($child->name))) {
                        $intent['category'] = $child->id;
                        break 2;
                    }
                }
            }
        }

        $brands = Brand::where('is_active', true)->get();
        foreach ($brands as $brand) {
            if (Str::contains($lower, mb_strtolower($brand->name))) {
                $intent['brand'] = $brand->id;
                break;
            }
        }

        $stopWords = [
            'cari', 'mencari', 'search', 'beli', 'mau', 'yang', 'ada',
            'untuk', 'saya', 'kamu', 'ini', 'itu', 'dengan', 'dan',
            'atau', 'murah', 'bagus', 'terbaik', 'new', 'baru',
            'produk', 'product', 'harga', 'price',
        ];

        $words = preg_split('/\s+/', $lower);
        $keywords = array_filter($words, function ($w) use ($stopWords) {
            return strlen($w) > 2 && !in_array($w, $stopWords) && !preg_match('/^\d+$/', $w);
        });

        $intent['keywords'] = array_values($keywords);

        if (!empty($intent['keywords']) || $intent['category'] || $intent['brand']) {
            $intent['products'] = $this->searchProductsByIntent($intent);
        }

        return $intent;
    }

    private function searchProductsByIntent(array $intent): array
    {
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with(['category', 'brand', 'store']);

        if (!empty($intent['keywords'])) {
            $query->where(function ($q) use ($intent) {
                foreach ($intent['keywords'] as $keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('short_description', 'like', "%{$keyword}%")
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$keyword}%"))
                      ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$keyword}%"));
                }
            });
        }

        if ($intent['category']) {
            $query->where('category_id', $intent['category']);
        }

        if ($intent['brand']) {
            $query->where('brand_id', $intent['brand']);
        }

        if ($intent['min_price']) {
            $query->where('price', '>=', $intent['min_price']);
        }

        if ($intent['max_price']) {
            $query->where('price', '<=', $intent['max_price']);
        }

        return $query->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                    'category' => $p->category->name ?? '-',
                    'average_rating' => $p->average_rating,
                    'reviews_count' => $p->reviews_count,
                ];
            })
            ->toArray();
    }

    private function greetingResponse(): array
    {
        return [
            'message' => "Halo! 👋 Selamat datang di WINKY AI.\n\nSaya asisten belanja AI yang siap membantu Anda menemukan produk terbaik. Ada yang bisa saya bantu?",
            'type' => 'text',
        ];
    }

    private function trendingResponse(): array
    {
        $trending = Cache::remember('ai:trending_products', 300, function () {
            return Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['category', 'brand'])
                ->orderByRaw('(SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) DESC')
                ->take(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'price' => $p->price,
                        'sale_price' => $p->sale_price,
                        'effective_price' => $p->getEffectivePrice(),
                        'image' => $p->image,
                        'brand' => $p->brand->name ?? '-',
                    ];
                })
                ->toArray();
        });

        if (empty($trending)) {
            return [
                'message' => 'Belum ada produk trending saat ini.',
                'type' => 'text',
            ];
        }

        return [
            'message' => '🔥 Ini produk populer saat ini:',
            'type' => 'products',
            'products' => $trending,
        ];
    }

    private function recommendationResponse(array $context): array
    {
        $excludeIds = $context['exclude_ids'] ?? [];

        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotIn('id', $excludeIds)
            ->with(['category', 'brand'])
            ->orderByRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id AND is_approved = 1) DESC NULLS LAST')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                    'average_rating' => $p->average_rating,
                ];
            })
            ->toArray();

        return [
            'message' => '⭐ Rekomendasi produk terbaik untuk Anda:',
            'type' => 'products',
            'products' => $products,
        ];
    }

    private function compareResponse(string $message): array
    {
        preg_match_all('/\b\w+\b/', $message, $words);
        $query = Product::where('is_active', true)
            ->where(function ($q) use ($words) {
                foreach ($words[0] as $word) {
                    if (strlen($word) > 3) {
                        $q->orWhere('name', 'like', "%{$word}%");
                    }
                }
            })
            ->take(4)
            ->get();

        if ($query->count() < 2) {
            return [
                'message' => 'Untuk membandingkan, saya butuh minimal 2 produk. Coba sebutkan nama produk yang ingin dibandingkan.',
                'type' => 'text',
            ];
        }

        return [
            'message' => 'Berikut produk yang bisa dibandingkan:',
            'type' => 'compare_suggestion',
            'products' => $query->map(fn($p) => [
                'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
            ])->toArray(),
        ];
    }

    private function stockCheckResponse(string $message): array
    {
        $product = Product::where('is_active', true)
            ->where('name', 'like', '%' . $message . '%')
            ->first();

        if (!$product) {
            $words = preg_split('/\s+/', $message);
            foreach ($words as $word) {
                if (strlen($word) > 3) {
                    $product = Product::where('is_active', true)
                        ->where('name', 'like', "%{$word}%")
                        ->first();
                    if ($product) break;
                }
            }
        }

        if (!$product) {
            return [
                'message' => 'Produk tidak ditemukan. Coba sebutkan nama produk yang lebih spesifik.',
                'type' => 'text',
            ];
        }

        $stock = $product->stock;
        if ($stock > 10) {
            $status = '✅ Stok tersedia';
        } elseif ($stock > 0) {
            $status = "⚠️ Stok terbatas ({$stock} tersisa)";
        } else {
            $status = '❌ Stok habis';
        }

        return [
            'message' => "{$status}\n\n**{$product->name}**\nHarga: Rp " . number_format($product->getEffectivePrice(), 0, ',', '.'),
            'type' => 'product_detail',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'effective_price' => $product->getEffectivePrice(),
                'stock' => $product->stock,
                'image' => $product->image,
            ],
        ];
    }

    private function searchProductResponse(array $intent): array
    {
        $products = $intent['products'] ?? [];

        if (empty($products)) {
            return [
                'message' => 'Produk tidak ditemukan untuk pencarian tersebut. Coba kata kunci lain atau lihat kategori produk kami.',
                'type' => 'text',
            ];
        }

        $count = count($products);
        $msg = "🔍 Ditemukan {$count} produk";

        if ($intent['min_price'] || $intent['max_price']) {
            if ($intent['min_price'] && $intent['max_price']) {
                $msg .= " (Rp " . number_format($intent['min_price'], 0, ',', '.') . " - Rp " . number_format($intent['max_price'], 0, ',', '.') . ")";
            } elseif ($intent['max_price']) {
                $msg .= " (maks. Rp " . number_format($intent['max_price'], 0, ',', '.') . ")";
            }
        }

        $msg .= ':';

        return [
            'message' => $msg,
            'type' => 'products',
            'products' => $products,
        ];
    }

    private function calculateProductScore(array $data): int
    {
        $score = 50;
        $score += min(20, ($data['average_rating'] ?? 0) * 4);
        $score += min(15, ($data['reviews_count'] ?? 0) * 0.5);
        $score += ($data['stock'] ?? 0) > 0 ? 10 : -20;
        $score += ($data['is_featured'] ?? false) ? 5 : 0;
        return max(0, min(100, (int) $score));
    }
}
