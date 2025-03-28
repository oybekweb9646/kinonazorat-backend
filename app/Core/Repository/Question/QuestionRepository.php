<?php

namespace App\Core\Repository\Question;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository
{
    public function findAllForAuthority(): Collection
    {
        $name = LanguageHelper::getName();
        $title = LanguageHelper::getTitle();
        $desc = LanguageHelper::getDesc();
        $query = Question::query()
            ->select([
                'id',
                'created_at',
                $title . ' as title',
                $desc . ' as desc',
            ])
            ->with([
                'readAuthority' => function ($query) use ($name) {
                    $query->select([
                        'question_authority.id',
                        $name . ' as name',
                        'question_authority.created_at'
                    ]);
                    $user = auth()->user();
                    if (isset($user->authority_id)) {
                        $query->where('authority_id', $user->authority_id);
                    }
                }
            ]);

        return $query->get();
    }

    public function findAllAsObject(): Collection
    {
        $query = Question::query();

        return $query->get();
    }

    public function getByIdObject(int $id)
    {
        return Question::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getById(int $id): array
    {
        $desc = LanguageHelper::getDesc();
        $title = LanguageHelper::getTitle();
        return Question::query()
            ->select([
                'id',
                $desc . ' as desc',
                $title . ' as title',
            ])
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();
    }
}
