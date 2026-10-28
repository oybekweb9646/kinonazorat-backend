<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tashqi tizimlar (MSPD) uchun server-to-server autentifikatsiya.
 * Statik Bearer token + ixtiyoriy IP allowlist. JWT/OneID ishlatilmaydi.
 */
class IntegrationAuth
{
    /**
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('integration.token');

        // Token sozlanmagan bo'lsa integratsiya yopiq.
        if ($expected === '') {
            return $this->deny('Integration API is not configured', 503);
        }

        $token = $request->bearerToken() ?: $request->header('X-Api-Token');

        if (empty($token) || !hash_equals($expected, (string) $token)) {
            return $this->deny('Invalid or missing integration token', 401);
        }

        $allowedIps = (array) config('integration.allowed_ips');

        if (!empty($allowedIps) && !$this->ipAllowed($request, $allowedIps)) {
            return $this->deny('IP address is not allowed', 403);
        }

        return $next($request);
    }

    /**
     * So'rovning manba IP'si allowlist'da bormi (proksilarni ham hisobga olib).
     *
     * @param Request $request
     * @param array<int, string> $allowedIps
     * @return bool
     */
    protected function ipAllowed(Request $request, array $allowedIps): bool
    {
        $candidates = array_merge(
            [$request->ip()],
            $request->ips(),
            [$request->header('X-Real-IP')],
            array_map('trim', explode(',', (string) $request->header('X-Forwarded-For')))
        );

        $candidates = array_values(array_unique(array_filter($candidates)));

        return count(array_intersect($candidates, $allowedIps)) > 0;
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return Response
     */
    protected function deny(string $message, int $statusCode): Response
    {
        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => [],
        ], $statusCode);
    }
}
