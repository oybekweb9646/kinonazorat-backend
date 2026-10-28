<?php

namespace App\Http\Middleware;

use App\Core\Service\Integration\IntegrationSettingService;
use App\Models\IntegrationLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tashqi tizimlar (MSPD) uchun server-to-server autentifikatsiya.
 * Statik token (Bearer yoki X-Api-Token) + ixtiyoriy IP allowlist.
 * Sozlamalar DB'dan (admin panel orqali boshqariladi), har so'rov log qilinadi.
 * JWT/OneID ishlatilmaydi.
 */
class IntegrationAuth
{
    public function __construct(
        protected IntegrationSettingService $settings,
    ) {
    }

    /**
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = $this->settings->current();
        $expected = (string) $setting->api_token;
        $clientIp = $this->trustedClientIp($request);

        // Token sozlanmagan bo'lsa integratsiya yopiq.
        if ($expected === '') {
            return $this->deny($request, $clientIp, false, 'not_configured', 'Integration API is not configured', 503);
        }

        $token = $request->bearerToken() ?: $request->header('X-Api-Token');
        $tokenValid = !empty($token) && hash_equals($expected, (string) $token);

        if (!$tokenValid) {
            return $this->deny($request, $clientIp, false, 'invalid_token', 'Invalid or missing integration token', 401);
        }

        // IP cheklovi yoqilgan va ro'yxat bo'sh bo'lmasa — tekshiramiz.
        $allowedIps = $this->settings->allowedIps($setting);

        if ($setting->ip_check_enabled && !empty($allowedIps)
            && ($clientIp === null || !in_array($clientIp, $allowedIps, true))) {
            return $this->deny($request, $clientIp, true, 'ip_denied', 'IP address is not allowed', 403);
        }

        $this->record($request, $clientIp, true, true, 'ok', null);

        return $next($request);
    }

    /**
     * Teskari proksi orqasida ishonchli mijoz IP'sini aniqlaydi.
     *
     * DIQQAT: X-Forwarded-For'ning BOSHIDAGI qiymatlarni mijoz soxta yubora oladi,
     * shuning uchun faqat X-Real-IP yoki XFF'ning OXIRGI (nginx qo'shgan) qiymatini olamiz.
     */
    protected function trustedClientIp(Request $request): ?string
    {
        $realIp = trim((string) $request->header('X-Real-IP'));
        if ($realIp !== '') {
            return $realIp;
        }

        $xff = (string) $request->header('X-Forwarded-For');
        if ($xff !== '') {
            $parts = array_values(array_filter(array_map('trim', explode(',', $xff))));
            if ($parts !== []) {
                return end($parts);
            }
        }

        return $request->ip();
    }

    /**
     * Rad etilgan so'rovni log qilib, JSON xatolik qaytaradi.
     */
    protected function deny(Request $request, ?string $clientIp, bool $tokenValid, string $reason, string $message, int $statusCode): Response
    {
        $this->record($request, $clientIp, $tokenValid, false, $reason, $statusCode);

        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => [],
        ], $statusCode);
    }

    /**
     * So'rov urinishini integration_logs jadvaliga yozadi (xatolik bo'lsa so'rovni buzmaydi).
     */
    protected function record(Request $request, ?string $clientIp, bool $tokenValid, bool $allowed, string $reason, ?int $statusCode): void
    {
        try {
            IntegrationLog::query()->create([
                'method' => $request->getMethod(),
                'path' => $request->path(),
                'client_ip' => $clientIp,
                'token_valid' => $tokenValid,
                'allowed' => $allowed,
                'reason' => $reason,
                'status_code' => $statusCode,
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'created_at' => Carbon::now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('integration_log_failed: ' . $e->getMessage());
        }
    }
}
