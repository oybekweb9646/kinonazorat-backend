<?php

namespace App\Core\Filter\Question;

use App\Core\Filter\BaseFilter;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class QuestionFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        return Question::query();
    }

    /**
     * Apply filter conditions based on the request.
     *
     * @return $this
     */
    public function apply(): self
    {
        $this->applyLikeFilters([
            'title_uz' => 'title_uz',
            'title_uzc' => 'title_uzc',
            'title_ru' => 'title_ru',
            'desc_uz' => 'desc_uz',
            'desc_uzc' => 'desc_uzc',
            'desc_ru' => 'desc_ru',
        ]);

        return $this;
    }
}
