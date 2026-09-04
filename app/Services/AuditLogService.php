<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogService
{
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    public static function logModelUpdate($model, array $changes, ?Request $request = null): AuditLog
    {
        $old = collect($changes)->mapWithKeys(fn($val, $key) => [$key => $model->getOriginal($key)])->toArray();

        return static::log(
            'updated',
            get_class($model),
            $model->id,
            $old,
            $changes,
            $request
        );
    }

    public static function logSellerApproval($seller, string $action, ?Request $request = null): AuditLog
    {
        return static::log(
            "seller.{$action}",
            get_class($seller),
            $seller->id,
            ['status' => $seller->getOriginal('status')],
            ['status' => $seller->status],
            $request
        );
    }

    public static function logWithdrawal($withdrawal, string $action, ?Request $request = null): AuditLog
    {
        return static::log(
            "withdrawal.{$action}",
            get_class($withdrawal),
            $withdrawal->id,
            ['status' => $withdrawal->getOriginal('status')],
            ['status' => $withdrawal->status, 'amount' => $withdrawal->amount],
            $request
        );
    }

    public static function logPayment(Payment $payment, string $action, ?Request $request = null): AuditLog
    {
        return static::log(
            "payment.{$action}",
            get_class($payment),
            $payment->id,
            ['status' => $payment->getOriginal('status')],
            ['status' => $payment->status],
            $request
        );
    }

    public static function logMarketplaceSetting(string $key, $oldValue, $newValue, ?Request $request = null): AuditLog
    {
        return static::log(
            'marketplace.setting_changed',
            'App\\Models\\MarketplaceSetting',
            null,
            [$key => $oldValue],
            [$key => $newValue],
            $request
        );
    }
}
