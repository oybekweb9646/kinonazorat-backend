<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Enums\Auth\AuthTypeEnum;
use App\Core\Enums\User\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $username
 * @property string $full_name
 * @property string $password
 * @property string $auth_type
 * @property string $role
 * @property string $egov_token
 * @property string $phone
 * @property string $position_name
 * @property boolean $is_juridical
 * @property integer $pin_fl
 * @property integer $stir
 * @property integer $authority_id
 * @property integer $status
 * @property string $date_of_birth
 * @property integer $region_id
 * @property Authority $authority
 * @property EnumSoatoRegion $region
 */
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'pin_fl',
        'auth_type',
        'status',
        'password',
        'role',
        'egov_token',
        'full_name',
        'stir',
        'date_of_birth',
        'phone',
        'is_juridical',
        'authority_id',
        'region_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function ban(): void
    {
        $this->status = UserStatusEnum::_BANNED;
    }

    public function unban(): void
    {
        $this->status = UserStatusEnum::_ACTIVE;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = Hash::make($password);
    }

    public function getAuthType(): string
    {
        return $this->auth_type;
    }

    public function setAuthType(string $auth_type): void
    {
        $this->auth_type = $auth_type;
    }


    public function getPinFl(): ?int
    {
        return $this->pin_fl;
    }

    public function getStir(): ?int
    {
        return $this->pin_fl;
    }

    public function getRegionId(): ?int
    {
        return $this->region_id;
    }

    public function setRegionId(?int $regionId): void
    {
        $this->region_id = $regionId;
    }

    public function setPinFl(?int $pin_fl): void
    {
        $this->pin_fl = $pin_fl;
    }

    public function getEgovToken(): ?string
    {
        return $this->egov_token;
    }

    public function setEgovToken(?string $egov_token): void
    {
        $this->egov_token = $egov_token;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::_ACTIVE->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isCheckPassword(string $value): bool
    {
        return Hash::check($value, $this->getPassword());
    }

    /**
     * @return bool
     */
    public function isNotEmptyPassword(): bool
    {
        return !empty($this->getPassword());
    }

    /**
     * @return bool
     */
    public function isEmptyPassword(): bool
    {
        return empty($this->getPassword());
    }

    /**
     * @return bool
     */
    public function isNotEmptyPinFl(): bool
    {
        return !empty($this->getPinFl());
    }

    /**
     * @return bool
     */
    public function isAuthTypeOneId(): bool
    {
        return $this->getAuthType() === AuthTypeEnum::_ONE_ID->value;
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class, 'authority_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(EnumSoatoRegion::class, 'region_id','id');
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'username' => $this->getUsername(),
            'auth_type' => $this->getAuthType()
        ];
    }
}
