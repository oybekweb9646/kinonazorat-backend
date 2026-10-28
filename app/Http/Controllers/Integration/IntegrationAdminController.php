<?php

namespace App\Http\Controllers\Integration;

use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Integration\IntegrationLogRepository;
use App\Core\Service\Integration\IntegrationSettingService;
use App\Models\IntegrationSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * MSPD integratsiyasini boshqarish (admin panel, faqat SUPER_ADMIN).
 * Token, IP cheklovi (token-only rejimi), ruxsat IP'lari va loglar.
 */
class IntegrationAdminController extends Controller
{
    public function __construct(
        public IntegrationSettingService $settingService,
        public IntegrationLogRepository $logRepository,
    ) {
    }

    /**
     * Joriy sozlama (token, IP cheklovi, ruxsat IP'lari).
     * GET integration-admin/settings
     */
    public function settings(): JsonResponse
    {
        $setting = $this->settingService->current();

        return Response::success('Integration settings', $this->present($setting));
    }

    /**
     * IP cheklovi va ro'yxatini yangilaydi.
     * POST integration-admin/settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ip_check_enabled' => ['sometimes', 'boolean'],
            'allowed_ips' => ['sometimes', 'array'],
            'allowed_ips.*' => ['string', 'ip'],
        ]);

        $setting = $this->settingService->update($data);

        return Response::success('Integration settings updated', $this->present($setting));
    }

    /**
     * Yangi token generatsiya qiladi.
     * POST integration-admin/regenerate-token
     */
    public function regenerateToken(): JsonResponse
    {
        $setting = $this->settingService->regenerateToken();

        return Response::success('Integration token regenerated', $this->present($setting));
    }

    /**
     * Integratsiya loglari (filtr + sahifalash).
     * POST integration-admin/logs
     */
    public function logs(Request $request): JsonResponse
    {
        $filters = array_merge($request->query(), $request->all());

        return Response::success('Integration logs', $this->logRepository->filter($filters));
    }

    /**
     * Loglar statistikasi.
     * GET integration-admin/logs/stats
     */
    public function logStats(): JsonResponse
    {
        return Response::success('Integration log stats', $this->logRepository->stats());
    }

    /**
     * @return array<string, mixed>
     */
    protected function present(IntegrationSetting $setting): array
    {
        return [
            'api_token' => $setting->api_token,
            'ip_check_enabled' => $setting->ip_check_enabled,
            'allowed_ips' => $this->settingService->allowedIps($setting),
        ];
    }
}
