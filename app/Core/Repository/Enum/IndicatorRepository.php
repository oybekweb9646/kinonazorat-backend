<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Indicator;
use Illuminate\Database\Eloquent\Collection;

class IndicatorRepository
{
    public function findAll($request): Collection
    {
        $name = LanguageHelper::getName();
        $query = Indicator::query()
            ->select([
                'id',
                'created_at',
                'type_id',
                'max_score',
                $name . ' as name'
            ])
            ->with([
                'indicatorType' => fn($query) => $query->select(['id', $name . ' as name', 'created_at'])
            ]);

        if ($request->has('type_id')) {
            $query->where('type_id', $request->get('type_id'));
        }
        return $query->get();
    }

    public function getById(int $id): array
    {
        $name = LanguageHelper::getName();
        return Indicator::query()
            ->select([
                'id',
                'type_id',
                $name . ' as name'
            ])
            ->where('id', $id)
            ->with([
                'indicatorType' => fn($query) => $query->select(['id', $name . ' as name', 'created_at'])
            ])
            ->firstOrFail()
            ->toArray();
    }

    public function getByIdObject(int $id)
    {
        return Indicator::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getByIndicatorTypeId(int $typeId): Collection
    {
        return Indicator::query()
            ->where('type_id', $typeId)
            ->get();
    }
}
