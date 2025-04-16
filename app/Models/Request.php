<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;


/**
 * App\Models\Request
 *
 * @property int $id
 * @property int $status
 * @property int $authority_id
 * @property int $stir
 * @property int $year
 * @property int $quarter
 * @property int $month
 * @property int $indicator_type_id
 * @property int $score
 * @property int $created_by
 * @property string $registered_date
 * @property string $closed_at
 * @property string $request_no
 * @mixin \Eloquent
 */
class Request extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->year = Carbon::parse($model->created_at)->year;
            $model->month = Carbon::parse($model->created_at)->month;
            $model->quarter = Carbon::parse($model->created_at)->quarter;
        });
    }

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'authority_id',
        'closed_at',
        'request_no',
        'status',
        'stir',
        'created_by',
        'year',
        'quarter',
        'month',
        'registered_date',
        'score',
        'indicator_type_id'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indicatorType(): BelongsTo
    {
        return $this->belongsTo(IndicatorType::class, 'indicator_type_id');
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class, 'authority_id');
    }

    public function scoreRequestIndicators(): HasMany
    {
        return $this->hasMany(ScoreRequestIndicator::class, 'request_id')->orderBy('indicator_id');
    }

    public function setStir(): void
    {
        $this->stir = $this->authority()->stir;
    }
}
