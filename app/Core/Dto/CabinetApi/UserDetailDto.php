<?php

namespace App\Core\Dto\CabinetApi;

use Illuminate\Support\Str;

class UserDetailDto
{
    public function __construct(
        protected array   $data,
        protected ?int    $status = null,
        protected ?string $role = null,
        protected ?int    $pin_fl = null,
        protected ?string $username = null,
        protected ?string $password = null,
        protected ?string $login_type = null,
        protected ?bool   $is_admin = false,
    )
    {
        foreach ($this->data as $key => $value) {
            if ($key === 'id') {
                $this->pm_user_id = $value;
                continue;
            }

            if (property_exists(self::class, $key) && !in_array($key, ['password', 'login_type'])) {
                $this->{$key} = $value;
            }
        }
    }

    public function getIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(?bool $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getPmUserId(): ?int
    {
        return $this->pm_user_id;
    }

    public function getAuthorityId(): ?int
    {
        return $this->authority_id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getMovedUserId(): ?int
    {
        return $this->moved_user_id;
    }

    public function getPinFl(): ?int
    {
        return $this->pin_fl;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getLoginType(): ?string
    {
        return $this->login_type;
    }

    public function setLoginType(?string $login_type): void
    {
        $this->login_type = $login_type;
    }

    public function getAuthKey(): ?string
    {
        return Str::random(50);
    }
}
