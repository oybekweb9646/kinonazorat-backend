<?php

namespace App\Core\Filter\Checklist;

use App\Core\Filter\BaseFilter;
use App\Models\Checklist;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class ChecklistFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        return Checklist::query();
    }

    /**
     * Apply filter conditions based on the request.
     *
     * @return $this
     */
    public function apply(): self
    {
        $this->applyLikeFilters([
            'name_uz' => 'name_uz',
            'name_uzc' => 'name_uzc',
            'name_ru' => 'name_ru',
        ]);

        $this->request->whenFilled('indicator_type_id', function ($value) {
            $this->query->where('indicator_type_id', $value);
        });

        return $this;
    }
}
