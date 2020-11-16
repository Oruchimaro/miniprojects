<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (\Session::has('locale')) {
            \App::setLocale(\Session::get('locale'));
        }

        return $next($request);
    }
}
