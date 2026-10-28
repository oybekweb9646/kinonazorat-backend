<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $api_token
 * @property bool $ip_check_enabled
 * @property string|null $allowed_ips
 */
class IntegrationSetting extends Model
{
    protected $table = 'integration_settings';

    protected $fillable = [
        'api_token',
        'ip_check_enabled',
        'allowed_ips',
    ];

    protected $casts = [
        'ip_check_enabled' => 'boolean',
    ];
}
