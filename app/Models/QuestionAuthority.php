<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\QuestionAuthority
 *
 * @property int $id
 * @property int $authority_id
 * @property int $question_id
 */
class QuestionAuthority extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'question_authority';

    protected $fillable = [
        'authority_id',
        'question_id',
    ];

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
