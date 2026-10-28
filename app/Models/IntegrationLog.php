<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $method
 * @property string|null $path
 * @property string|null $client_ip
 * @property bool $token_valid
 * @property bool $allowed
 * @property string|null $reason
 * @property int|null $status_code
 * @property string|null $user_agent
 * @property string|null $created_at
 */
class IntegrationLog extends Model
{
    protected $table = 'integration_logs';

    public $timestamps = false; // faqat created_at qo'lda yoziladi

    protected $fillable = [
        'method',
        'path',
        'client_ip',
        'token_valid',
        'allowed',
        'reason',
        'status_code',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'token_valid' => 'boolean',
        'allowed' => 'boolean',
        'created_at' => 'datetime',
    ];
}
