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
     * So'rovning haqiqiy manba IP'si allowlist'da bormi.
     *
     * DIQQAT: faqat ishonchli (teskari proksi tomonidan o'rnatilgan) manbalardan
     * foydalanamiz. X-Forwarded-For'ning BOSHIDAGI qiymatlarni mijoz o'zi soxta
     * yubora oladi, shuning uchun ularga ishonmaymiz — faqat X-Real-IP va XFF'ning
     * OXIRGI (nginx qo'shgan) qiymatini olamiz.
     *
     * @param Request $request
     * @param array<int, string> $allowedIps
     * @return bool
     */
    protected function ipAllowed(Request $request, array $allowedIps): bool
    {
        $clientIp = $this->trustedClientIp($request);

        return $clientIp !== null && in_array($clientIp, $allowedIps, true);
    }

    /**
     * Teskari proksi orqasida ishonchli mijoz IP'sini aniqlaydi.
     *
     * @param Request $request
     * @return string|null
     */
    protected function trustedClientIp(Request $request): ?string
    {
        // 1) X-Real-IP — nginx tomonidan $remote_addr'dan o'rnatiladi (soxtalashtirib bo'lmaydi).
        $realIp = trim((string) $request->header('X-Real-IP'));
        if ($realIp !== '') {
            return $realIp;
        }

        // 2) X-Forwarded-For'ning OXIRGI qiymati — proksi qo'shadi, mijoz boshqarolmaydi.
        $xff = (string) $request->header('X-Forwarded-For');
        if ($xff !== '') {
            $parts = array_values(array_filter(array_map('trim', explode(',', $xff))));
            if ($parts !== []) {
                return end($parts);
            }
        }

        // 3) Proksi yo'q bo'lsa — to'g'ridan-to'g'ri ulanish IP'si.
        return $request->ip();
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
