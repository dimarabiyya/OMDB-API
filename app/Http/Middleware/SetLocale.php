<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Cek apakah ada session 'locale'
        if (session()->has('locale')) {
            // Set bahasa aplikasi sesuai session
            App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }
}