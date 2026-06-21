<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $table = 'error_logs';

    protected $fillable = [
        'user_id',
        'username',
        'user_role',
        'method',
        'url',
        'message',
        'exception_class',
        'file',
        'line',
        'trace',
        'ip_address',
        'status_code',
    ];
}