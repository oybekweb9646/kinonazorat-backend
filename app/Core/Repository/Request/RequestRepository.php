<?php

namespace App\Core\Repository\Request;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

class RequestRepository
{
    public function findAll(): Collection
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'createdBy'
            ])
            ->get();
    }

    public function getById(int $id)
    {
        return Request::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getByIdWithRelations(int $id)
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->where('id', $id)
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name','*']);
                },
                'createdBy'
            ])
            ->firstOrFail();
    }


    public function getByIdWithIndicators(int $id)
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->where('id', $id)
            ->with([
                'scoreRequestIndicators' => function ($query) use ($name) {
                    $query->with([
                        'indicator' => function ($query) use ($name) {
                            $query->select(['id', $name . ' as name']);
                        },
                        'file'
                    ]);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name', '*']);
                }
            ])
            ->firstOrFail();
    }

    public function stat($max, $min): int
    {
        return Request::query()
            ->where('score', '>=', $min)
            ->where('score', '<=', $max)
            ->count();
    }
}
