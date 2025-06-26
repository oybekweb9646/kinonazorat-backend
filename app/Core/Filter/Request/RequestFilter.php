<?php

namespace App\Core\Filter\Request;

use App\Core\Enums\Role\RoleEnum;
use App\Core\Filter\BaseFilter;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Request;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class RequestFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        $name = LanguageHelper::getName();
        $user = auth()->user();

        return Request::query()
            ->whereHas('authority.directorSoato', function ($q) use ($user) {
                if ($user->role == RoleEnum::_TERRITORIAL_RESPONSIBLE->value) {
                    $q->where('parent_id', $user->organization?->region_id);
                }
            })
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name', '*']);
                },
                'createdBy',
                'updatedBy',
            ]);
    }

    /**
     * Apply filter conditions based on the request.
     *
     * @return $this
     */
    public function apply(): self
    {
        $this->request->whenFilled('stir', function ($value) {
            $this->query->where('stir', 'ilike', '%' . $value . '%');
        });

        $this->request->whenFilled('max_score', function ($value) {
            $this->query->where('score', '<=', $value);
        });

        $this->request->whenFilled('status', function ($value) {
            $this->query->where('status', $value);
        });

        $this->request->whenFilled('min_score', function ($value) {
            $this->query->where('score', '>=', $value);
        });

        $this->request->whenFilled('indicator_type_id', function ($value) {
            $this->query->where('indicator_type_id', $value);
        });

        $this->request->whenFilled('authority_id', function ($value) {
            $this->query->where('authority_id', $value);
        });

        $this->request->whenFilled('created_by', function ($value) {
            $this->query->where('created_by', $value);
        });

        return $this;
    }
}
