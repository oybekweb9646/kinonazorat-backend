<?php

namespace App\Core\Repository\Question;

use App\Models\QuestionAuthority;

class QuestionAuthorityRepository
{
    public function getByIdObject(int $id)
    {
        return QuestionAuthority::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getByAuthorityAndQuestion(int $authorityId, int $questionId)
    {
        return QuestionAuthority::query()
            ->where('authority_id', $authorityId)
            ->where('question_id', $questionId)
            ->first();
    }
}
