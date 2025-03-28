<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_uzc
 */
class Checklist extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'checklists';

    protected $fillable = [
        'indicator_type_id',
        'name_uz',
        'name_ru',
        'name_uzc',
    ];

    public function checkedAuthority(): BelongsToMany
    {
        return $this->belongsToMany(Authority::class, 'checklist_authority', 'checklist_id', 'authority_id');
    }

    public function indicatorType(): BelongsTo
    {
        return $this->belongsTo(IndicatorType::class, 'indicator_type_id');
    }
}
