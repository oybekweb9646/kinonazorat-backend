<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\LinkScoreRequestIndicatorFiles
 *
 * @property int $id
 * @property int $score_request_indicator_id
 * @property int $file_id
 * @property ScoreRequestIndicator $scoreRequestIndicator
 */
class LinkScoreRequestIndicatorFiles extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'link_score_request_indicator_files';
    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'score_request_indicator_id',
        'file_id',
    ];

    public function scoreRequestIndicator(): BelongsTo
    {
        return $this->belongsTo(ScoreRequestIndicator::class,'score_request_indicator_id','id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
