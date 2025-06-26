<?php

namespace App\Core\Filter\Authority;

use App\Core\Enums\Role\RoleEnum;
use App\Core\Filter\BaseFilter;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Authority;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;

class AuthorityFilter extends BaseFilter
{
    protected function getBaseQuery(): BuilderAlias
    {
        $name = LanguageHelper::getName();
        $user = auth()->user();
        return Authority::query()
            ->select([
                $name . ' as name',
                '*'
            ])
            ->whereHas('directorSoato', function ($q) use ($user) {
                if ($user->role == RoleEnum::_TERRITORIAL_RESPONSIBLE->value) {
                    $q->where('parent_id', $user->organization?->region_id);
                }
            });
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

        $this->request->whenFilled('name', function ($value) {
            $this->query->where(function ($query) use ($value) {
                $query->where('name_uz', 'ilike', "%$value%")
                    ->orWhere('name_ru', 'ilike', "%$value%")
                    ->orWhere('name_uzc', 'ilike', "%$value%");
            });
        });

        $this->request->whenFilled('stir', function ($value) {
            $this->query->where('stir', $value);
        });

        $this->request->whenFilled('is_checked_checklist', function ($value) {
            $this->query->where('is_checked_checklist', $value);
        });

        $this->request->whenFilled('is_checked_question', function ($value) {
            $this->query->where('is_checked_question', $value);
        });

        return $this;
    }
}
