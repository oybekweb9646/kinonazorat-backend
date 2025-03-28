<?php

namespace App\Core\Filter\IndicatorType;

use App\Core\Filter\BaseFilter;
use App\Models\IndicatorType;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class IndicatorTypeFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        return IndicatorType::query();
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

        return $this;
    }
}
