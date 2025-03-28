<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;


/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_uzc
 * @property string $title_uz
 * @property string $title_ru
 * @property string $title_uzc
 */
class Question extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'questions';
    protected $fillable = [
        'desc_uz',
        'desc_ru',
        'desc_uzc',
        'title_uz',
        'title_uzc',
        'title_ru'
    ];

    public function readAuthority(): BelongsToMany
    {
        return $this->belongsToMany(Authority::class, 'question_authority', 'question_id', 'authority_id');
    }
}
