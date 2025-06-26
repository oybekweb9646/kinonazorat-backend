<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository
{
    public function findAll($request): Collection
    {
        $name = LanguageHelper::getName();
        $query = Organization::query()
            ->select([
                'id',
                'created_at',
                'region_id',
                'inn',
                $name . ' as name'
            ])
            ->with([
                'region' => fn($query) => $query->select(['id', $name . ' as name', 'created_at'])
            ]);

        if ($request->has('region_id')) {
            $query->where('region_id', $request->get('region_id'));
        }
        return $query->get();
    }

    public function getById(int $id): array
    {
        $name = LanguageHelper::getName();
        return Organization::query()
            ->select([
                'id',
                $name . ' as name'
            ])
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();
    }

    public function getByIdObject(int $id)
    {
        return Organization::query()
            ->where('id', $id)
            ->firstOrFail();
    }
}
