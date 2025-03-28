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
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_uzc
 * @property integer $type_id
 * @property boolean $is_active
 * @property integer $max_score
 */
class Indicator extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_ru',
        'name_uz',
        'name_uzc',
        'is_active',
        'type_id',
        'max_score'
    ];

    /**
     * @return BelongsTo
     */
    public function indicatorType(): BelongsTo
    {
        return $this->belongsTo(IndicatorType::class, 'type_id');
    }
}
