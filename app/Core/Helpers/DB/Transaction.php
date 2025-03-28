<?php

namespace App\Core\Helpers\DB;

use Illuminate\Support\Facades\DB;

class Transaction
{
    public function wrap(callable $function)
    {
        return DB::transaction($function);
    }
}
