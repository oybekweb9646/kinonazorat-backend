<?php

namespace App\Core\Repository\Request;

use App\Core\Enums\Request\State;
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
                        'file' => function ($query) {
                            $query->select(['id', "original_name AS name","path"]);
                        },
                        'updatedBy' => function ($query) {
                            $query->select(['id', 'username']);
                        },
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

    public function findNoConfirmed(int $authorityId,?int $indicatorTypeId = null)
    {
        return Request::query()
            ->where('authority_id', $authorityId)
            ->when($indicatorTypeId, function ($query) use ($indicatorTypeId) {
                return $query->where('indicator_type_id', $indicatorTypeId);
            })
            ->where('status', '<',State::CONFIRMED->value)
            ->first();
    }

}
