<?php

namespace App\Core\Filter\User;

use App\Core\Filter\BaseFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends BaseFilter
{
    protected function getBaseQuery(): Builder
    {
        return User::query();
    }

    /**
     * Apply filter conditions based on the request.
     *
     * @return $this
     */
    public function apply(): self
    {
        $this->request->whenFilled('username', function ($value) {
            $this->query->where('username', 'ilike', '%' . $value . '%');
        });

        $this->request->whenFilled('full_name', function ($value) {
            $this->query->where('full_name', 'ilike', '%' . $value . '%');
        });

        $this->request->whenFilled('pin_fl', function ($value) {
            $this->query->where('pin_fl', 'ilike', '%' . $value . '%');
        });

        $this->request->whenFilled('stir', function ($value) {
            $this->query->where('stir', 'ilike', '%' . $value . '%');
        });

        $this->request->whenFilled('role', function ($value) {
            $this->query->where('role', $value);
        });

        $this->request->whenFilled('is_juridical', function ($value) {
            $this->query->where('is_juridical', $value);
        });

        return $this;
    }
}
