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
 * @property string $name_uzc'
 * @property int $stir
 * @property string|null $billing_address
 * @property string|null $billing_soato
 * @property string|null $director_address
 * @property string|null $director_soato
 * @property string|null $director_lastName
 * @property string|null $director_middleName
 * @property string|null $director_firstName
 * @property string|null $director_gender
 * @property string|null $director_nationality
 * @property string|null $director_citizenship
 * @property string|null $director_passportNumber
 * @property string|null $director_stir
 * @property string|null $director_pinfl
 * @property string|null $director_countryCode
 * @property string|null $director_passportSeries
 * @property integer|null $indicator_type_id
 */
class Authority extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'authority';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_uz',
        'name_ru',
        'name_uzc',
        'name_uzc',
        'billing_address',
        'director_address',
        'director_soato',
        'address',
        'billing_soato',
        'director_lastName',
        'director_firstName',
        'director_middleName',
        'director_gender',
        'director_nationality',
        'director_citizenship',
        'director_passportNumber',
        'director_stir',
        'director_pinfl',
        'director_countryCode',
        'director_passportSeries',
        'is_checked_checklist',
        'is_checked_question',
        'indicator_type_id',
        'stir'
    ];

    public function indicatorType(): BelongsTo
    {
        return $this->belongsTo(IndicatorType::class, 'indicator_type_id');
    }

    public function directorSoato(): BelongsTo
    {
        return $this->belongsTo(EnumSoatoRegion::class, 'director_soato');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_authority', 'authority_id', 'question_id');
    }

    public function checklists(): BelongsToMany
    {
        return $this->belongsToMany(Checklist::class, 'checklist_authority', 'authority_id', 'checklist_id');
    }
}
