<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\IndicatorType;
use Illuminate\Database\Eloquent\Collection;

class IndicatorTypeRepository
{
    public function findAll(): Collection
    {
        $name = LanguageHelper::getName();
        return IndicatorType::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name'
            ])
            ->get();
    }

    public function getById(int $id)
    {
        $name = LanguageHelper::getName();
        return IndicatorType::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name'
            ])
            ->where('id', $id)
            ->firstOrFail();
    }
}
