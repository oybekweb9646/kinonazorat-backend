<?php

namespace App\Core\Repository\Log;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Log;
use Illuminate\Database\Eloquent\Collection;

class LogRepository
{
    public function findAllByRequest($id)
    {
        $name = LanguageHelper::getName();
        return Log::query()
            ->where('request_id', $id)
            ->with([
                'user',
                'scoreRequestIndicator' => function ($query) use ($name) {
                    $query->with([
                        'indicator' => function ($query) use ($name) {
                            $query->select(['id', $name . ' as name']);
                        }
                    ]);
                }
            ])
            ->orderBy('id', 'desc');
    }
}
