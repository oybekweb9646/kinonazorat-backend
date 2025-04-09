<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\ScoreRequestIndicator
 *
 * @property int $id
 * @property int $indicator_id
 * @property int $request_id
 * @property int $score
 * @property int $max_score
 * @property int $file_id
 * @property int $created_by
 * @property int $updated_by
 * @property string $file_name
 */
class ScoreRequestIndicator extends BaseModel
{
    use HasFactory, Notifiable;

    protected $table = 'score_request_indicator';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'indicator_id',
        'request_id',
        'score',
        'max_score',
        'file_id',
        'file_name',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function setPoint(): void
    {
        $this->score = $this->max_score;
    }

    public function removePoint(): void
    {
        $this->score = 0;
    }

    public function setFile(int $fileId): void
    {
        $this->file_id = $fileId;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
