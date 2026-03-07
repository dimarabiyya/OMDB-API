<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Membaca session 'locale', jika tidak ada default ke 'en'
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        
        return $next($request);
    }
}