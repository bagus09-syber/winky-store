<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

Route::get('/flash-sale', [\App\Http\Controllers\FlashSaleController::class, 'index'])->name('flash-sale');
Route::get('/flash-sale/{promo}', [\App\Http\Controllers\FlashSaleController::class, 'detail'])->name('flash-sale.detail');
Route::get('/flash-sale/check-discount', [\App\Http\Controllers\FlashSaleController::class, 'checkDiscount'])->name('flash-sale.check-discount');

Route::get('/', function () {
    $flashSaleProducts = App\Models\Product::with(['category', 'brand'])
        ->where('is_active', true)
        ->whereNotNull('sale_price')
        ->where('sale_price', '>', 0)
        ->whereColumn('sale_price', '<', 'price')
        ->take(4)
        ->get();

    $featuredProducts = App\Models\Product::with(['category', 'brand'])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->take(8)
        ->get();

    $newProducts = App\Models\Product::with(['category', 'brand'])
        ->where('is_active', true)
        ->latest()
        ->take(8)
        ->get();

    $categories = App\Models\Category::where('is_active', true)
        ->whereNull('parent_id')
        ->with(['children' => function ($q) {
            $q->where('is_active', true)->withCount('products');
        }])
        ->get()
        ->map(function ($cat) {
            $cat->total_products_count = $cat->products_count + $cat->children->sum('products_count');
            return $cat;
        })
        ->take(18);

    $brands = App\Models\Brand::where('is_active', true)
        ->withCount('products')
        ->take(8)
        ->get();

    $recommendationService = app(App\Services\AI\RecommendationService::class);
    $trendingProducts = $recommendationService->getTrendingProducts(8);
    $recentlyViewed = $recommendationService->getRecentlyViewed(8);

    return view('home', compact('flashSaleProducts', 'featuredProducts', 'newProducts', 'categories', 'brands', 'trendingProducts', 'recentlyViewed'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/search', [ProductController::class, 'searchApi'])->name('api.search');
Route::get('/api/quick-view', [ProductController::class, 'quickView'])->name('api.quickView');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Account Routes
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/security', [AccountController::class, 'security'])->name('security');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AccountController::class, 'editAddress'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::put('/addresses/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('addresses.setDefault');
    Route::get('/rewards', [AccountController::class, 'rewards'])->name('rewards');
    Route::get('/referrals', [AccountController::class, 'referrals'])->name('referrals');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

// Cart Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/api/validate-voucher', [CartController::class, 'validateVoucher'])->name('api.validateVoucher');
});

// Wishlist Routes
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/api/shipping-rates', [CheckoutController::class, 'getShippingRates'])->name('api.shippingRates');
    Route::get('/affiliate', [\App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate.index');
    Route::get('/affiliate/links', [\App\Http\Controllers\AffiliateController::class, 'links'])->name('affiliate.links');
    Route::post('/affiliate/links', [\App\Http\Controllers\AffiliateController::class, 'createLink'])->name('affiliate.createLink');
    Route::post('/affiliate/click/{link}', [\App\Http\Controllers\AffiliateController::class, 'trackClick'])->name('affiliate.trackClick');
    Route::get('/affiliate/stats', [\App\Http\Controllers\AffiliateController::class, 'conversionStats'])->name('affiliate.stats');
});

// Order Routes
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/return', [\App\Http\Controllers\ReturnController::class, 'store'])->name('returns.store');
    Route::get('/orders/{order}/return', [\App\Http\Controllers\ReturnController::class, 'create'])->name('returns.create');
});

// Review Routes
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/helpful', [\App\Http\Controllers\ReviewController::class, 'toggleHelpful'])->name('reviews.helpful');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/{order:order_number}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order:order_number}', [PaymentController::class, 'process'])->name('payment.process');
    Route::patch('/payment/{order:order_number}/simulate', [PaymentController::class, 'processPayment'])->name('payment.simulate');
    Route::post('/payment/{order:order_number}/callback', [PaymentController::class, 'simulateCallback'])->name('payment.callback');
    Route::get('/payment/{order:order_number}/success', [PaymentController::class, 'success'])->name('payment.success');
});

// Payment Webhook (no auth - called by payment gateway)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Help & Support
Route::get('/help', [\App\Http\Controllers\SupportController::class, 'help'])->name('help.index');
Route::get('/contact', [\App\Http\Controllers\SupportController::class, 'contact'])->name('help.contact');
Route::post('/contact', [\App\Http\Controllers\SupportController::class, 'submitTicket'])->name('help.submitTicket');

// Trust Pages
Route::get('/about', fn() => view('pages.about'))->name('pages.about');
Route::get('/privacy', fn() => view('pages.privacy'))->name('pages.privacy');
Route::get('/terms', fn() => view('pages.terms'))->name('pages.terms');
Route::get('/returns-policy', fn() => view('pages.returns'))->name('pages.returns');

// Store Pages (Public)
Route::get('/store/{store:slug}', [\App\Http\Controllers\StorePageController::class, 'show'])->name('store.show');
Route::get('/store/{store:slug}/products', [\App\Http\Controllers\StorePageController::class, 'products'])->name('store.products');
Route::get('/store/{store:slug}/reviews', [\App\Http\Controllers\StorePageController::class, 'reviews'])->name('store.reviews');

// Seller Registration
Route::middleware('auth')->group(function () {
    Route::get('/seller/register', [\App\Http\Controllers\Seller\SellerRegistrationController::class, 'showRegister'])->name('seller.register');
    Route::post('/seller/register', [\App\Http\Controllers\Seller\SellerRegistrationController::class, 'register'])->name('seller.register.store');
});

// Seller Center
Route::get('/ai/insights', [\App\Http\Controllers\Seller\SellerAIController::class, 'insights'])->name('ai.insights');

// Seller Performance
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Seller\SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [\App\Http\Controllers\Seller\SellerProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Seller\SellerProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Seller\SellerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Seller\SellerProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Seller\SellerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Seller\SellerProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle', [\App\Http\Controllers\Seller\SellerProductController::class, 'toggle'])->name('products.toggle');
    Route::get('/orders', [\App\Http\Controllers\Seller\SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Seller\SellerOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Seller\SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/wallet', [\App\Http\Controllers\Seller\SellerWalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\Seller\SellerWalletController::class, 'requestWithdrawal'])->name('wallet.withdraw');
    Route::get('/performance', [\App\Http\Controllers\SellerPerformanceController::class, 'index'])->name('performance');
    Route::get('/performance/dashboard', [\App\Http\Controllers\SellerPerformanceController::class, 'dashboard'])->name('performance.dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);

    // Brands
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->except(['show']);

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_number}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order:order_number}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order:order_number}/ship', [\App\Http\Controllers\Admin\OrderController::class, 'shipOrder'])->name('orders.shipOrder');

    // Vouchers
    Route::get('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::patch('/vouchers/{voucher}/toggle', [\App\Http\Controllers\Admin\VoucherController::class, 'toggle'])->name('vouchers.toggle');

    // Shipping Settings
    Route::get('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::put('/shipping/warehouse', [\App\Http\Controllers\Admin\ShippingController::class, 'updateWarehouse'])->name('shipping.updateWarehouse');
    Route::post('/shipping/methods', [\App\Http\Controllers\Admin\ShippingController::class, 'storeMethod'])->name('shipping.storeMethod');
    Route::put('/shipping/methods/{method}', [\App\Http\Controllers\Admin\ShippingController::class, 'updateMethod'])->name('shipping.updateMethod');
    Route::patch('/shipping/methods/{method}/toggle', [\App\Http\Controllers\Admin\ShippingController::class, 'toggleMethod'])->name('shipping.toggleMethod');
    Route::delete('/shipping/methods/{method}', [\App\Http\Controllers\Admin\ShippingController::class, 'destroyMethod'])->name('shipping.destroyMethod');

    // Reviews
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Support
    Route::get('/support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('support.show');
    Route::put('/support/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'update'])->name('support.update');

    // Returns
    Route::get('/returns', [\App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [\App\Http\Controllers\Admin\ReturnController::class, 'show'])->name('returns.show');
    Route::put('/returns/{return}', [\App\Http\Controllers\Admin\ReturnController::class, 'update'])->name('returns.update');

    // Product Reports
    Route::get('/reports/products', [\App\Http\Controllers\Admin\ProductReportController::class, 'index'])->name('reports.product.index');
    Route::get('/reports/products/{report}', [\App\Http\Controllers\Admin\ProductReportController::class, 'show'])->name('reports.product.show');
    Route::put('/reports/products/{report}/review', [\App\Http\Controllers\Admin\ProductReportController::class, 'review'])->name('reports.product.review');

    // Store Reports
    Route::get('/reports/stores', [\App\Http\Controllers\Admin\StoreReportController::class, 'index'])->name('reports.store.index');
    Route::get('/reports/stores/{report}', [\App\Http\Controllers\Admin\StoreReportController::class, 'show'])->name('reports.store.show');
    Route::put('/reports/stores/{report}/review', [\App\Http\Controllers\Admin\StoreReportController::class, 'review'])->name('reports.store.review');

    // Affiliates
    Route::get('/affiliates', [\App\Http\Controllers\Admin\AffiliatesController::class, 'index'])->name('admin.affiliates.index');
    Route::patch('/affiliates/{affiliate}/approve', [\App\Http\Controllers\Admin\AffiliatesController::class, 'approve'])->name('admin.affiliates.approve');
    Route::patch('/affiliates/{affiliate}/suspend', [\App\Http\Controllers\Admin\AffiliatesController::class, 'suspend'])->name('admin.affiliates.suspend');
    Route::patch('/affiliates/{affiliate}/rate', [\App\Http\Controllers\Admin\AffiliatesController::class, 'setRate'])->name('admin.affiliates.rate');
    Route::get('/affiliates/{affiliate}/earnings', [\App\Http\Controllers\Admin\AffiliatesController::class, 'reviewEarnings'])->name('admin.affiliates.earnings');

    // Sellers
    Route::get('/sellers', [\App\Http\Controllers\Admin\AdminSellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{seller}', [\App\Http\Controllers\Admin\AdminSellerController::class, 'show'])->name('sellers.show');
    Route::patch('/sellers/{seller}/approve', [\App\Http\Controllers\Admin\AdminSellerController::class, 'approve'])->name('sellers.approve');
    Route::patch('/sellers/{seller}/reject', [\App\Http\Controllers\Admin\AdminSellerController::class, 'reject'])->name('sellers.reject');
    Route::patch('/sellers/{seller}/suspend', [\App\Http\Controllers\Admin\AdminSellerController::class, 'suspend'])->name('sellers.suspend');
    Route::patch('/sellers/{seller}/verify', [\App\Http\Controllers\Admin\AdminSellerController::class, 'toggleVerification'])->name('sellers.toggleVerification');

    // Withdrawals
    Route::get('/withdrawals', [\App\Http\Controllers\Admin\AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [\App\Http\Controllers\Admin\AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::patch('/withdrawals/{withdrawal}/approve', [\App\Http\Controllers\Admin\AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::patch('/withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Admin\AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    Route::patch('/withdrawals/{withdrawal}/pay', [\App\Http\Controllers\Admin\AdminWithdrawalController::class, 'pay'])->name('withdrawals.pay');

    // Marketplace Settings
    Route::get('/marketplace', [\App\Http\Controllers\Admin\AdminMarketplaceController::class, 'index'])->name('marketplace.index');
    Route::put('/marketplace', [\App\Http\Controllers\Admin\AdminMarketplaceController::class, 'update'])->name('marketplace.update');
});

// AI Commerce Routes
Route::post('/api/ai/chat', [\App\Http\Controllers\AIController::class, 'chat'])->name('ai.chat');
Route::get('/api/ai/conversations', [\App\Http\Controllers\AIController::class, 'getConversations'])->name('ai.conversations');
Route::get('/api/ai/conversations/{id}', [\App\Http\Controllers\AIController::class, 'getConversation'])->name('ai.conversations.show');
Route::delete('/api/ai/conversations/{id}', [\App\Http\Controllers\AIController::class, 'deleteConversation'])->name('ai.conversations.delete');
Route::post('/api/ai/search', [\App\Http\Controllers\AIController::class, 'searchProducts'])->name('ai.search');
Route::get('/api/ai/suggestions', [\App\Http\Controllers\AIController::class, 'getSuggestions'])->name('ai.suggestions');
Route::get('/api/ai/recommendations', [\App\Http\Controllers\AIController::class, 'getRecommendations'])->name('ai.recommendations');
Route::get('/api/ai/similar/{product}', [\App\Http\Controllers\AIController::class, 'getSimilarProducts'])->name('ai.similar');
Route::get('/api/ai/frequently-bought/{product}', [\App\Http\Controllers\AIController::class, 'getFrequentlyBoughtTogether'])->name('ai.fbt');

// Compare Routes
Route::get('/compare', [\App\Http\Controllers\CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/add', [\App\Http\Controllers\CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{product}', [\App\Http\Controllers\CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare/clear', [\App\Http\Controllers\CompareController::class, 'clear'])->name('compare.clear');
Route::get('/compare/count', [\App\Http\Controllers\CompareController::class, 'getCount'])->name('compare.count');

// Seller AI Routes
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::post('/ai/generate-title', [\App\Http\Controllers\Seller\SellerAIController::class, 'generateTitle'])->name('ai.generateTitle');
    Route::post('/ai/generate-description', [\App\Http\Controllers\Seller\SellerAIController::class, 'generateDescription'])->name('ai.generateDescription');
    Route::post('/ai/generate-seo', [\App\Http\Controllers\Seller\SellerAIController::class, 'generateSEO'])->name('ai.generateSEO');
    Route::post('/ai/analyze/{product}', [\App\Http\Controllers\Seller\SellerAIController::class, 'analyzeProduct'])->name('ai.analyzeProduct');
    Route::get('/ai/insights', [\App\Http\Controllers\Seller\SellerAIController::class, 'insights'])->name('ai.insights');
});