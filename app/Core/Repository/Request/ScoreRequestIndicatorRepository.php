<?php

namespace App\Core\Repository\Request;

use App\Models\ScoreRequestIndicator;
use Illuminate\Database\Eloquent\Collection;

class ScoreRequestIndicatorRepository
{
    public function getByRequestId(int $id): Collection
    {
        return ScoreRequestIndicator::query()
            ->where('request_id', $id)
            ->get();
    }

    public function getById(int $id)
    {
        return ScoreRequestIndicator::query()
            ->where('id', $id)
            ->firstOrFail();
    }
}
