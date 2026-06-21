<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    private static array $SKIP_METHODS = ['GET', 'HEAD', 'OPTIONS'];
    private static array $SKIP_URLS = ['auth/login', 'auth/refresh', 'auth/logout'];

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (in_array($request->method(), self::$SKIP_METHODS)) {
            return;
        }

        foreach (self::$SKIP_URLS as $url) {
            if (str_contains($request->path(), $url)) {
                return;
            }
        }

        try {
            $user = auth('api')->user();
            $requestData = $request->except(['password', 'token', 'access_token']);

            ActivityLog::create([
                'user_id'      => $user?->id,
                'username'     => $user?->username ?? $user?->full_name,
                'user_role'    => $user?->role,
                'method'       => $request->method(),
                'url'          => $request->path(),
                'action'       => $this->resolveAction($request),
                'request_data' => $requestData ?: null,
                'ip_address'   => $request->ip(),
                'status_code'  => $response->getStatusCode(),
            ]);
        } catch (\Throwable) {
            // Log yozishda xatolik bo'lsa tizim to'xtamasin
        }
    }

    private function resolveAction(Request $request): string
    {
        $path = $request->path();
        $method = $request->method();

        $map = [
            'authority/create'   => 'Tashkilot yaratish',
            'authority/update'   => 'Tashkilot yangilash',
            'authority/delete'   => 'Tashkilot o\'chirish',
            'request/create'     => 'Baholash yaratish',
            'request/scored'     => 'Baholash yakunlash',
            'mib-integration/get'=> 'MIB so\'rov',
            'user/create'        => 'Foydalanuvchi yaratish',
            'user/update'        => 'Foydalanuvchi yangilash',
            'user/delete'        => 'Foydalanuvchi o\'chirish',
        ];

        foreach ($map as $pattern => $label) {
            if (str_contains($path, $pattern)) {
                return $label;
            }
        }

        return strtoupper($method) . ' /' . $path;
    }
}