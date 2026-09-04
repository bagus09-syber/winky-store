<?php

namespace App\Log\Processors;

use Monolog\Processor\Processor;

class SensitiveDataFilter extends Processor
{
    /**
     * Array of sensitive keys that should be masked in logs.
     *
     * @var array
     */
    protected $sensitiveKeys = [
        'password',
        'passphrase',
        'secret',
        'api_key',
        'apikey',
        'auth',
        'token',
        'credit_card',
        'card_number',
        'cvv',
        'ssn',
        'social_security',
        'api_secret',
        'private_key',
        'app_key',
        'db_password',
        'mail_password',
        'redis_password',
    ];

    /**
     * Process the record.
     *
     * @param array $record the record to process
     *
     * @return array
     */
    public function process(array $record): array
    {
        $message = $record['message'];
        $context = $record['context'] ?? [];

        // Mask sensitive values in context
        $context = $this->maskSensitiveValues($context);

        // Mask sensitive values in message if it's a string
        if (is_string($message)) {
            $message = $this->maskSensitiveValuesInString($message);
            $record['message'] = $message;
        }

        $record['context'] = $context;

        return $record;
    }

    /**
     * Mask sensitive values in an array/context.
     *
     * @param array $data
     *
     * @return array
     */
    protected function maskSensitiveValues(array $data): array
    {
        foreach ($data as $key => &$value) {
            $lowerKey = strtolower($key);

            // Check if key contains sensitive keywords
            foreach ($this->sensitiveKeys as $sensitiveKey) {
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
                    break; // Only mask once per key
                }
            }

            // Recursively process nested arrays
            if (is_array($value)) {
                $value = $this->maskSensitiveValues($value);
            }
        }

        return $data;
    }

    /**
     * Mask sensitive values in a string message.
     *
     * @param string $message
     *
     * @return string
     */
    protected function maskSensitiveValuesInString(string $message): string
    {
        // Common patterns for sensitive data
        $patterns = [
            '/[a-f0-9]{32,}/i' => '[REDACTED]', // API keys hex strings
            '/password["\s]*[:=]["\s]*[^,\s]+/i' => 'password=[REDACTED]',
            '/api_key["\s]*[:=]["\s]*[^,\s]+/i' => 'api_key=[REDACTED]',
            '/api_secret["\s]*[:=]["\s]*[^,\s]+/i' => 'api_secret=[REDACTED]',
            '/token["\s]*[:=]["\s]*[^,\s]+/i' => 'token=[REDACTED]',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        return $message;
    }
}