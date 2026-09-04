<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $currency = $request->input('currency')
            ?? $request->cookie('currency')
            ?? Session::get('currency')
            ?? ($request->user() ? $request->user()->currency : null)
            ?? 'IDR';

        $supportedCurrencies = ['IDR', 'USD', 'SGD', 'MYR', 'EUR'];

        if (!in_array($currency, $supportedCurrencies)) {
            $currency = 'IDR';
        }

        Session::put('currency', $currency);

        view()->share('currentCurrency', $currency);
        view()->share('currencyService', app(\App\Services\CurrencyService::class));

        $response = $next($request);
        $response->cookie('currency', $currency, 525600);

        return $response;
    }
}
