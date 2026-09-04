<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->input('lang')
            ?? $request->cookie('locale')
            ?? Session::get('locale')
            ?? ($request->user() ? $request->user()->locale : null)
            ?? config('app.locale', 'id');

        $supportedLocales = ['id', 'en'];

        if (!in_array($locale, $supportedLocales)) {
            $locale = 'id';
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        $response = $next($request);
        $response->cookie('locale', $locale, 525600);

        return $response;
    }
}
