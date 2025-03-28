<?php

namespace App\Core\Filter;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFilter
{
    /** @var Builder */
    protected Builder $query;

    /** @var FormRequest */
    protected FormRequest $request;

    public function __construct(FormRequest $request)
    {
        $this->request = $request;
        $this->query = $this->getBaseQuery();
    }

    /**
     * Return the base query builder instance for the filter.
     *
     * @return BuilderAlias
     */
    abstract protected function getBaseQuery(): BuilderAlias;

    // Generic LIKE filter
    protected function applyLikeFilters(array $fields): void
    {
        foreach ($fields as $requestField => $dbField) {
            $this->request->whenFilled($requestField, function ($value) use ($dbField) {
                $this->query->where($dbField, 'ilike', '%' . $value . '%');
            });
        }
    }

    protected function applyExactFilters(array $fields): void
    {
        foreach ($fields as $requestField => $dbField) {
            $this->request->whenFilled($requestField, function ($value) use ($dbField) {
                $this->query->where($dbField, $value);
            });
        }
    }


    /**
     * Apply common pagination.
     *
     * @param int $defaultSize
     * @return LengthAwarePaginator
     */
    public function paginate(int $defaultSize = 10): LengthAwarePaginator
    {
        return $this->query->orderBy('id')
            ->paginate(
                $this->request->input('size', $defaultSize),
                ['*'],
                'page',
                $this->request->input('page', 1)
            );
    }

}

