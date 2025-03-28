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
 * @property boolean $is_checked
 * @property integer $checklist_id
 * @property integer $authority_id
 */
class ChecklistAuthority extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'checklist_authority';
    protected $fillable = [
        'checklist_id',
        'authority_id',
        'is_checked'
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class);
    }
}
