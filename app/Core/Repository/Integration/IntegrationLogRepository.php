<?php

namespace App\Core\Repository\Integration;

use App\Models\IntegrationLog;

class IntegrationLogRepository
{
    /**
     * Integratsiya loglarini filtr va sahifalash bilan qaytaradi.
     *
     * Filtrlar: method, reason, client_ip, allowed (bool), date_from, date_to, page, per_page.
     *
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function filter(array $filters): array
    {
        $perPage = (int) ($filters['per_page'] ?? $filters['size'] ?? 20);
        $perPage = max(1, min($perPage, 100));

        $query = IntegrationLog::query();

        if (!empty($filters['method'])) {
            $query->where('method', strtoupper((string) $filters['method']));
        }

        if (!empty($filters['reason'])) {
            $query->where('reason', $filters['reason']);
        }

        if (!empty($filters['client_ip'])) {
            $query->where('client_ip', 'like', '%' . $filters['client_ip'] . '%');
        }

        if (array_key_exists('allowed', $filters) && $filters['allowed'] !== null && $filters['allowed'] !== '') {
            $query->where('allowed', filter_var($filters['allowed'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $paginator = $query->orderByDesc('id')->paginate($perPage);

        return [
            'items' => collect($paginator->items())->map(fn (IntegrationLog $log) => [
                'id' => $log->id,
                'method' => $log->method,
                'path' => $log->path,
                'client_ip' => $log->client_ip,
                'token_valid' => $log->token_valid,
                'allowed' => $log->allowed,
                'reason' => $log->reason,
                'status_code' => $log->status_code,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at?->toDateTimeString(),
            ])->values(),
            'meta' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }

    /**
     * Qisqa statistika (admin dashboard uchun).
     *
     * @return array<string, int>
     */
    public function stats(): array
    {
        return [
            'total' => IntegrationLog::query()->count(),
            'allowed' => IntegrationLog::query()->where('allowed', true)->count(),
            'denied' => IntegrationLog::query()->where('allowed', false)->count(),
            'today' => IntegrationLog::query()->whereDate('created_at', today())->count(),
        ];
    }
}
