<?php

namespace App\Core\Repository\checklist;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Checklist;
use Illuminate\Database\Eloquent\Collection;

class ChecklistRepository
{
    public function findAllForAuthority($id): Collection
    {
        $name = LanguageHelper::getName();
        $query = Checklist::query()
            ->select([
                'id',
                'created_at',
                $name . ' as name',
            ])
            ->where('indicator_type_id', $id)
            ->with([
                'checkedAuthority' => function ($query) use ($name) {
                    $query->select([
                        'checklist_authority.id',
                        $name . ' as name',
                        'checklist_authority.created_at',
                        'checklist_authority.is_checked'
                    ]);
                    $user = auth()->user();
                    if (isset($user->authority_id)) {
                        $query->where('authority_id', $user->authority_id);
                    }
                }
            ]);

        return $query->get();
    }

    public function getByIdObject(int $id)
    {
        return Checklist::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getById(int $id): array
    {
        $name = LanguageHelper::getName();
        return Checklist::query()
            ->select([
                'id',
                $name . ' as name',
            ])
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                }
            ])
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();
    }
}
