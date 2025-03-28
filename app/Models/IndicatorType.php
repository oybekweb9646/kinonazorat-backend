<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_uzc
 */
class IndicatorType extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_uz',
        'name_ru',
        'name_uzc'
    ];
}
