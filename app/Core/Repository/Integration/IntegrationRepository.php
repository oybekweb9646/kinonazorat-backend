<?php

namespace App\Core\Repository\Integration;

use App\Core\Enums\Request\RiskLevel;
use App\Core\Enums\Request\State;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Authority;
use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Tashqi tizimlar (MSPD) uchun faqat o'qish (read-only) ma'lumot manbai.
 * Yakunlangan risk-tahlil natijalarini shakllantiradi.
 */
class IntegrationRepository
{
    /**
     * Integratsiya uchun ko'rsatiladigan standart holatlar: yakunlangan natijalar.
     *
     * @return array<int, int>
     */
    protected function finalizedStatuses(): array
    {
        return [
            State::SCORED->value,
            State::SEND_FOR_INSECTION->value,
            State::ARCHIVED->value,
        ];
    }

    /**
     * Risk natijalari ro'yxati (paginatsiya + filtrlar bilan).
     *
     * Qo'llanadigan filtrlar: stir, indicator_type_id, status, year, quarter,
     * month, date_from, date_to, page, per_page.
     *
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function riskResults(array $filters): array
    {
        $perPage = (int) ($filters['per_page'] ?? 20);
        $perPage = max(1, min($perPage, 100));

        $paginator = $this->buildQuery($filters)->paginate($perPage);

        return [
            'items' => collect($paginator->items())
                ->map(fn (Request $request) => $this->formatResult($request))
                ->values(),
            'meta' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }

    /**
     * Berilgan STIR bo'yicha subyekt va uning barcha risk natijalari
     * (har bir natija indikatorlar bo'yicha batafsil).
     *
     * STIR topilmasa null qaytaradi (controller 404 beradi).
     *
     * @param int $stir
     * @return array<string, mixed>|null
     */
    public function riskResultByStir(int $stir): ?array
    {
        $name = LanguageHelper::getName();

        $authority = Authority::query()
            ->with('indicatorType')
            ->where('stir', $stir)
            ->first();

        if ($authority === null) {
            return null;
        }

        $results = Request::query()
            ->where('authority_id', $authority->id)
            ->whereIn('status', $this->finalizedStatuses())
            ->with([
                'indicatorType',
                'authority',
                'scoreRequestIndicators.indicator',
            ])
            ->orderByDesc('id')
            ->get();

        $director = trim(implode(' ', array_filter([
            $authority->director_lastName,
            $authority->director_firstName,
            $authority->director_middleName,
        ])));

        return [
            'organization' => [
                'stir' => $authority->stir,
                'name' => $authority->{$name} ?? $authority->name_uz,
                'address' => $authority->address,
                'director' => $director !== '' ? $director : null,
                'indicator_type' => $authority->indicatorType?->{$name} ?? $authority->indicatorType?->name_uz,
            ],
            'results' => $results
                ->map(fn (Request $request) => $this->formatResult($request, true))
                ->values(),
        ];
    }

    /**
     * Filtrlangan asosiy so'rov.
     *
     * @param array<string, mixed> $filters
     * @return Builder
     */
    protected function buildQuery(array $filters): Builder
    {
        $query = Request::query()
            ->with(['indicatorType', 'authority'])
            ->whereIn('status', $this->resolveStatuses($filters['status'] ?? null));

        if (!empty($filters['stir'])) {
            $stir = $filters['stir'];
            $query->where(function ($q) use ($stir) {
                $q->where('stir', $stir)
                    ->orWhereHas('authority', fn ($a) => $a->where('stir', $stir));
            });
        }

        if (!empty($filters['indicator_type_id'])) {
            $query->where('indicator_type_id', (int) $filters['indicator_type_id']);
        }

        if (!empty($filters['year'])) {
            $query->where('year', (int) $filters['year']);
        }

        if (!empty($filters['quarter'])) {
            $query->where('quarter', (int) $filters['quarter']);
        }

        if (!empty($filters['month'])) {
            $query->where('month', (int) $filters['month']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('id');
    }

    /**
     * Status filtrini ro'yxatga keltiradi. Bo'sh bo'lsa standart yakunlangan holatlar.
     *
     * @param mixed $status
     * @return array<int, int>
     */
    protected function resolveStatuses(mixed $status): array
    {
        if ($status === null || $status === '') {
            return $this->finalizedStatuses();
        }

        $list = is_array($status) ? $status : explode(',', (string) $status);
        $list = array_values(array_filter(array_map('intval', $list)));

        return $list !== [] ? $list : $this->finalizedStatuses();
    }

    /**
     * Bitta risk natijasini tashqi tizim uchun formatlaydi.
     *
     * @param Request $request
     * @param bool $withIndicators
     * @return array<string, mixed>
     */
    protected function formatResult(Request $request, bool $withIndicators = false): array
    {
        $name = LanguageHelper::getName();
        $state = State::tryFrom((int) $request->status);
        $score = $request->score !== null ? (int) $request->score : null;

        $data = [
            'request_no' => $request->request_no,
            'stir' => $request->stir ?? $request->authority?->stir,
            'organization' => $request->authority?->{$name} ?? $request->authority?->name_uz,
            'indicator_type' => $request->indicatorType?->{$name} ?? $request->indicatorType?->name_uz,
            'score' => $score,
            'risk_level' => RiskLevel::fromScore($score)?->value,
            'status' => (int) $request->status,
            'status_label' => $state?->label(),
            'period' => [
                'year' => $request->year,
                'quarter' => $request->quarter,
                'month' => $request->month,
            ],
            'registered_date' => $request->registered_date,
            'closed_at' => $request->closed_at,
            'order' => [
                'number' => $request->order_number,
                'date' => $request->order_date,
                'inspector' => $request->order_inspector,
            ],
            'act' => [
                'number' => $request->act_number,
                'date' => $request->act_date,
            ],
            'created_at' => $request->created_at?->toDateTimeString(),
        ];

        if ($withIndicators) {
            $data['indicators'] = $request->scoreRequestIndicators
                ->map(fn ($indicator) => [
                    'name' => $indicator->indicator?->{$name} ?? $indicator->indicator?->name_uz,
                    'score' => $indicator->score !== null ? (int) $indicator->score : null,
                    'max_score' => (int) $indicator->max_score,
                ])
                ->values();
        }

        return $data;
    }
}
