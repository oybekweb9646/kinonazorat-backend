<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\EnumSoatoRegion;
use Illuminate\Database\Eloquent\Collection;

class SoatoRegionsRepository
{
    public function findAll(): Collection
    {
        $name = LanguageHelper::getName();
        return EnumSoatoRegion::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name',
                'parent_id'
            ])
            ->get();
    }

    public function findParentAll(): Collection
    {
        $name = LanguageHelper::getName();
        return EnumSoatoRegion::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name',
                'parent_id'
            ])
            ->where('parent_id', null)->get();
    }

    public function getById(int $id)
    {
        $name = LanguageHelper::getName();
        return EnumSoatoRegion::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name',
                'parent_id'
            ])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function findById(int $id): Collection
    {
        return EnumSoatoRegion::query()
            ->where('id', $id)
            ->get();
    }
}
