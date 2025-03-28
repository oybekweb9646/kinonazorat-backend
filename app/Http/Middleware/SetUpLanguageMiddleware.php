<?php

namespace App\Http\Middleware;

use App\Core\Enums\Lang\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUpLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Language::tryFrom($request->header('Accept-Language', 'uz')) ?? Language::UZ;

        app()->setLocale($locale->value);

        return $next($request);
    }
}
