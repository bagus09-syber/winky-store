<?php

namespace App\Health;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Monolog\Logger;

class HealthCheckService
{
    const STATUS_HEALTHY = 'healthy';
    const STATUS_UNHEALTHY = 'unhealthy';
    const STATUS_DEGRADED = 'degraded';

    protected array $checks = [];
    protected ?string $status = null;
    protected ?string $detail = null;
    protected array $metrics = [];

    public function addCheck(string $name, callable $check): self
    {
        $this->checks[$name] = $check;
        return $this;
    }

    public function evaluate(): array
    {
        $this->status = self::STATUS_HEALTHY;
        $this->metrics = [];

        foreach ($this->checks as $name => $check) {
            try {
                $result = $check();

                if (is_array($result)) {
                    $this->metrics[$name] = $result;
                } else {
                    $this->metrics[$name] = ['status' => $result];
                }

                if ($result === false || $result['status'] ?? self::STATUS_HEALTHY === self::STATUS_UNHEALTHY) {
                    $this->status = self::STATUS_UNHEALTHY;
                    $this->detail = "Check failed: {$name}";
                } elseif (
                    $result['status'] ?? self::STATUS_HEALTHY === self::STATUS_DEGRADED
                    && $this->status !== self::STATUS_UNHEALTHY
                ) {
                    $this->status = self::STATUS_DEGRADED;
                }
            } catch (\Exception $e) {
                $this->status = self::STATUS_UNHEALTHY;
                $this->metrics[$name] = [
                    'status' => self::STATUS_UNHEALTHY,
                    'error' => $e->getMessage(),
                ];
                $this->detail = "Check exception: {$name} - " . $e->getMessage();
            }
        }

        return $this->getReport();
    }

    public function getStatus(): string
    {
        return $this->status ?? self::STATUS_UNHEALTHY;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function getMetrics(): array
    {
        return $this->metrics;
    }

    public function getReport(): array
    {
        return [
            'status' => $this->status,
            'detail' => $this->detail,
            'timestamp' => now()->toISO8601String(),
            'metrics' => $this->metrics,
        ];
    }

    /**
     * Run all health checks and return JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function asJson(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getReport(), $this->status === self::STATUS_HEALTHY ? 200 : 503);
    }

    /**
     * Application core checks.
     */
    public static function createDefaultChecks(): array
    {
        return [
            'application' => fn () => self::checkApplication(),
            'database' => fn () => self::checkDatabase(),
            'cache' => fn () => self::checkCache(),
            'routes' => fn () => self::checkRoutes(),
            'view' => fn () => self::checkView(),
        ];
    }

    protected static function checkApplication(): bool|array
    {
        $appName = config('app.name', 'unknown');
        $env = config('app.env');
        $debug = config('app.debug');

        if ($debug && $env !== 'production') {
            return [
                'status' => 'ok',
                'environment' => $env,
                'application' => $appName,
            ];
        }

        return true;
    }

    protected static function checkDatabase(): bool|array
    {
        try {
            DB::connection()->getPdo();
            $tableCount = DB::table('information_schema.tables')
                ->where('table_schema', config('database.connections.sqlite.database', 'winky_store'))
                ->count();

            return [
                'status' => 'ok',
                'connection' => 'active',
                'tables' => $tableCount,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected static function checkCache(): bool|array
    {
        try {
            Cache::forever('health_check', true);
            Cache::forget('health_check');

            return [
                'status' => 'ok',
                'connection' => 'active',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected static function checkRoutes(): bool|array
    {
        try {
            $routeCount = Route::getRoutes()->getCount();

            return [
                'status' => 'ok',
                'route_count' => $routeCount,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected static function checkView(): bool|array
    {
        try {
            View::make('home');

            return [
                'status' => 'ok',
                'view_loading' => 'ok',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
}