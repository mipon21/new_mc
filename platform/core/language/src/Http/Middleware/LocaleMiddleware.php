<?php

namespace Botble\Language\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Language;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Language::getCurrentLocaleCode();
        
        if (!$locale) {
            $locale = 'de';
        }

        app()->setLocale($locale);

        return $next($request);
    }
} 