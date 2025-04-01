<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\EnumSoatoRegion
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_uzc
 * @property int $parent_id
 */
class EnumSoatoRegion extends Model
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
        'name_uzc',
        'parent_id',
    ];

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'region_id','id');
    }
}
