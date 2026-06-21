<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'username',
        'user_role',
        'method',
        'url',
        'action',
        'request_data',
        'ip_address',
        'status_code',
    ];

    protected $casts = [
        'request_data' => 'array',
    ];
}