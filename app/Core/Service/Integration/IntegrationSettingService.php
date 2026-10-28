<?php

namespace App\Core\Service\Integration;

use App\Models\IntegrationSetting;

/**
 * Integratsiya sozlamalarini DB'da boshqaradi (admin panel uchun).
 * Birinchi murojaatda .env (config/integration.php) qiymatlaridan seed qiladi.
 */
class IntegrationSettingService
{
    /**
     * Joriy sozlama (yagona qator). Bo'lmasa .env'dan yaratiladi.
     */
    public function current(): IntegrationSetting
    {
        $setting = IntegrationSetting::query()->orderBy('id')->first();

        if ($setting === null) {
            $allowedIps = (array) config('integration.allowed_ips');

            $setting = IntegrationSetting::query()->create([
                'api_token' => (string) config('integration.token'),
                'ip_check_enabled' => !empty($allowedIps),
                'allowed_ips' => implode(',', $allowedIps),
            ]);
        }

        return $setting;
    }

    /**
     * Ruxsat etilgan IP'lar massivi.
     *
     * @return array<int, string>
     */
    public function allowedIps(IntegrationSetting $setting): array
    {
        return array_values(array_filter(array_map(
            'trim',
            explode(',', (string) $setting->allowed_ips)
        )));
    }

    /**
     * IP cheklovi va ro'yxatini yangilaydi.
     *
     * @param array{ip_check_enabled?: bool, allowed_ips?: array<int,string>|string} $data
     */
    public function update(array $data): IntegrationSetting
    {
        $setting = $this->current();

        if (array_key_exists('ip_check_enabled', $data)) {
            $setting->ip_check_enabled = (bool) $data['ip_check_enabled'];
        }

        if (array_key_exists('allowed_ips', $data)) {
            $ips = is_array($data['allowed_ips'])
                ? $data['allowed_ips']
                : explode(',', (string) $data['allowed_ips']);

            $ips = array_values(array_filter(array_map('trim', $ips)));
            $setting->allowed_ips = implode(',', $ips);
        }

        $setting->save();

        return $setting;
    }

    /**
     * Yangi token generatsiya qiladi.
     */
    public function regenerateToken(): IntegrationSetting
    {
        $setting = $this->current();
        $setting->api_token = bin2hex(random_bytes(32));
        $setting->save();

        return $setting;
    }
}
