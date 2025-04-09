<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($item) {
            if (Auth::check()) {
                $item->created_by = Auth::id();
            }
        });

        static::updating(function ($item) {
            if (Auth::check())
                $item->updated_by = Auth::id();
        });
    }
}
