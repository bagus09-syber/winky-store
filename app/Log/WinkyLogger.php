<?php

namespace App\Log;

use Illuminate\Support\Facades\Log as IlluminateLog;
use Monolog\Level;
use Monolog\Formatter\LineFormatter;
use DateTime;

class WinkyLogger
{
    const CHANNEL_CUSTOM = 'custom';
    const CHANNEL_ORDERS = 'orders';
    const CHANNEL_PAYMENT = 'payment';
    const CHANNEL_SECURITY = 'security';
    const CHANNEL_WEBHOOK = 'webhook';
    const CHANNEL_AUDIT = 'audit';

    /**
     * Log a general informational message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->info($message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->debug($message, $context);
    }

    /**
     * Log a notice message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function notice(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->notice($message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->warning($message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->error($message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function critical(string $message, array $context = []): void
    {
        IlluminateLog::channel(self::CHANNEL_CUSTOM)->critical($message, $context);
    }

    /**
     * Log order-related events.
     *
     * @param string $event
     * @param array $data
     * @return void
     */
    public static function order(string $event, array $data = []): void
    {
        IlluminateLog::channel(self::CHANNEL_ORDERS)->info($event, $data);
    }

    /**
     * Log payment-related events (webhooks, transactions).
     * Pastikan data sensitif difilter melalui SensitiveDataProcessor.
     *
     * @param string $event
     * @param array $data
     * @return void
     */
    public static function payment(string $event, array $data = []): void
    {
        // Filter sensitive data before logging
        $filteredData = self::filterSensitivePaymentData($data);
        IlluminateLog::channel(self::CHANNEL_PAYMENT)->info($event, $filteredData);
    }

    /**
     * Log security events (login attempts, auth failures, etc.).
     *
     * @param string $event
     * @param array $data
     * @return void
     */
    public static function security(string $event, array $data = []): void
    {
        // Filter sensitive security data
        $filteredData = self::filterSensitiveSecurityData($data);
        IlluminateLog::channel(self::CHANNEL_SECURITY)->warning($event, $filteredData);
    }

    /**
     * Log webhook events with signature verification.
     *
     * @param string $provider
     * @param string $event
     * @param array $data
     * @param string|null $signature
     * @return void
     */
    public static function webhook(string $provider, string $event, array $data = [], ?string $signature = null): void
    {
        $logData = [
            'provider' => $provider,
            'event' => $event,
            'signature_present' => !is_null($signature),
        ];

        // Only log signature status, not the actual signature
        if (!is_null($signature)) {
            $logData['signature_length'] = strlen($signature);
        }

        // Filter sensitive data from payload
        $filteredData = self::filterSensitiveWebhookData($data);
        $logData['data'] = $filteredData;

        IlluminateLog::channel(self::CHANNEL_WEBHOOK)->info('Webhook received', $logData);
    }

    /**
     * Log admin audit events (admin actions, changes, etc.).
     *
     * @param string $action
     * @param int|string $userId
     * @param array $details
     * @return void
     */
    public static function audit(string $action, $userId, array $details = []): void
    {
        $logData = [
            'action' => $action,
            'user_id' => $userId,
            'details' => $details,
        ];

        IlluminateLog::channel(self::CHANNEL_AUDIT)->info('Admin audit', $logData);
    }

    /**
     * Filter sensitive data from payment logs.
     *
     * @param array $data
     * @return array
     */
    protected static function filterSensitivePaymentData(array $data): array
    {
        $sensitiveKeys = [
            'card_number',
            'cvv',
            'expiry',
            'expire',
            'ccv',
            'billing_address',
            'full_name',
            'phone',
            'email',
            'amount',
            'transaction_id',
            'secret',
            'key',
        ];

        return self::maskSensitiveValues($data, $sensitiveKeys);
    }

    /**
     * Filter sensitive data from security logs.
     *
     * @param array $data
     * @return array
     */
    protected static function filterSensitiveSecurityData(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'token',
            'secret',
            'api_key',
            'auth',
        ];

        return self::maskSensitiveValues($data, $sensitiveKeys);
    }

    /**
     * Filter sensitive data from webhook logs.
     *
     * @param array $data
     * @return array
     */
    protected static function filterSensitiveWebhookData(array $data): array
    {
        $sensitiveKeys = [
            'card_number',
            'cvv',
            'password',
            'secret',
            'api_key',
        ];

        return self::maskSensitiveValues($data, $sensitiveKeys);
    }

    /**
     * Mask sensitive values in array based on sensitive keys list.
     *
     * @param array $data
     * @param array $sensitiveKeys
     * @return array
     */
    protected static function maskSensitiveValues(array $data, array $sensitiveKeys): array
    {
        foreach ($data as $key => &$value) {
            $lowerKey = strtolower($key);

            foreach ($sensitiveKeys as $sensitiveKey) {
                if (strpos($lowerKey, $sensitiveKey) !== false) {
                    if (is_string($value)) {
                        $value = '[REDACTED]';
                    } elseif (is_array($value)) {
                        $value = array_map(function ($item) {
                            return is_string($item) ? '[REDACTED]' : $item;
                        }, $value);
                    } elseif (is_object($value)) {
                        $value = '[REDACTED]';
                    }
                    break;
                }
            }

            if (is_array($value)) {
                $value = self::maskSensitiveValues($value, $sensitiveKeys);
            }
        }

        return $data;
    }
}