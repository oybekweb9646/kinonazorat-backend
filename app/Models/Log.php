<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $action
 * @property string $user_ip
 * @property string $user_agent
 * @property  $data
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 * @property integer $request_id
 * @property integer $authority_id
 * @property integer $score_request_indicator_id
 */
class Log extends Model
{
    protected $table = 'log';
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'action',
        'user_id',
        'request_id',
        'authority_id',
        'score_request_indicator_id',
        'user_ip',
        'user_agent',
        'data',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scoreRequestIndicator(): BelongsTo
    {
        return $this->belongsTo(ScoreRequestIndicator::class, 'score_request_indicator_id');
    }
}
