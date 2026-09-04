<?php

// Push Notification Architecture - Development Mode
// File: app/Services/PushNotificationService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PushNotificationService
{
    const CACHE_KEY_PERMISSIONS = 'winky:push:permissions';
    const CACHE_KEY_SUBSCRIPTIONS = 'winky:push:subscriptions';

    public static function requestPermission(): string
    {
        // Development mode: simulate permission grant
        // In production with Firebase, would use JavaScript Notification API
        $granted = true; // Development simulation

        if ($granted) {
            Cache::put(self::CACHE_KEY_PERMISSIONS, 'granted', 86400); // 24 hours
            Log::info('Push notification permission granted', [auth()->id() ?? 'anonymous']);
            return 'granted';
        }

        Cache::put(self::CACHE_KEY_PERMISSIONS, 'denied', 86400);
        return 'denied';
    }

    public static function isPermissionGranted(): bool
    {
        return Cache::get(self::CACHE_KEY_PERMISSIONS) === 'granted';
    }

    public static function subscribeUser(string $endpoint, string $p256dh, string $auth): bool
    {
        if (!self::isPermissionGranted()) {
            return false;
        }

        $subscription = [
            'endpoint' => $endpoint,
            'p256dh' => $p256dh,
            'auth' => $auth,
            'created_at' => now(),
        ];

        Cache::put(self::CACHE_KEY_SUBSCRIPTIONS, $subscription, 604800); // 7 days
        Log::info('Push subscription saved', []);

        return true;
    }

    public static function getSubscription(): ?array
    {
        return Cache::get(self::CACHE_KEY_SUBSCRIPTIONS);
    }

    public static function unsubscribe(): bool
    {
        return Cache::forget(self::CACHE_KEY_SUBSCRIPTIONS);
    }

    // Notification categories
    public static function notify(string $type, array $data): bool
    {
        if (!self::isPermissionGranted()) {
            Log::warning('Push notification skipped - permission not granted', [$type]);
            return false;
        }

        $categoryMap = [
            'orders' => 'orders',
            'shipping' => 'shipping',
            'promotions' => 'promotions',
            'flash_sale' => 'flash_sale',
            'loyalty' => 'loyalty',
            'referral' => 'referral',
            'wishlist_stock' => 'wishlist_stock',
            'seller' => 'seller',
        ];

        $category = $categoryMap[$type] ?? 'default';

        // Development mode: log the notification
        // In production, would send via Firebase Cloud Messaging
        $notification = [
            'title' => self::getNotificationTitle($type, $data),
            'body' => self::getNotificationBody($type, $data),
            'icon' => asset('images/icons/icon-96x96.png'),
            'badge' => asset('images/icons/icon-72x72.png'),
            'tag' => $type,
            'data' => $data,
            'category' => $category,
        ];

        Log::info('Push notification sent', [
            'category' => $category,
            'title' => $notification['title'],
            'user' => auth()->id() ?? 'anonymous',
        ]);

        return true;
    }

    private static function getNotificationTitle(string $type, array $data): string
    {
        return match($type) {
            'orders' => 'Status Order Terbaru',
            'shipping' => 'Update Pengiriman',
            'promotions' => 'Promo Baru',
            'flash_sale' => 'Flash Sale Aktif',
            'loyalty' => 'Hadiah Loyalitas',
            'referral' => 'Hadiah Referral',
            'wishlist_stock' => 'Stok Favorit Kadaluarsa',
            'seller' => 'Notifikasi Penjual',
            default => 'Notifikasi WINKY STORE',
        };
    }

    private static function getNotificationBody(string $type, array $data): string
    {
        return match($type) {
            'orders' => isset($data['status']) ? "Status order Anda: {$data['status']}" : 'Status order berubah',
            'shipping' => isset($data['tracking']) ? "Paket dikirim! Tracking: {$data['tracking']}" : 'Barang sedang dikirim',
            'promotions' => 'Ada promo menarik untuk Anda',
            'flash_sale' => 'Flash sale sedang berlangsung!',
            'loyalty' => 'Poin loyalty Anda telah diperbarui',
            'referral' => 'Teman baru terdaftar!',
            'wishlist_stock' => 'Stok produk di wishlist Anda hampir habis',
            'seller' => 'Pesan dari penjual',
            default => 'Anda memiliki notifikasi baru',
        };
    }
}