<?php

namespace App\Core\Filter\Organization;

use App\Core\Filter\BaseFilter;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class OrganizationFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        return Organization::query();
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

        $this->request->whenFilled('region_id', function ($value) {
            $this->query->where('region_id', $value);
        });

        return $this;
    }
}
