<?php

namespace App\Core\Repository\User;

use App\Core\Enums\User\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param User $user
     * @param array $params
     * @return void
     */
    public function save(User $user, array $params = []): void
    {
        if (!$user->save($params)) {
            throw new \RuntimeException(__('client.User save error'));
        }
    }

    public function findAll(): Collection
    {
        return User::query()
            ->get();
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): User|null
    {
        return User::query()
            ->where('id', $id)
            ->first();
    }

    public function getById(int $id)
    {
        return User::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return User|Builder
     */
    public function getByIdActive(int $id): Builder|User
    {
        return User::query()
            ->where('id', $id)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return Builder|User|null
     */
    public function findByIdActive(int $id): User|null|Builder
    {
        return User::query()
            ->where('id', $id)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->first();
    }

    /**
     * @param string $username
     * @param int $pin_fl
     * @return bool
     */
    public function isExistsUser(string $username, int $pin_fl): bool
    {
        return User::query()
            ->where('username', $username)
            ->where('pin_fl', $pin_fl)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->exists();
    }

    /**
     * @param string $username
     * @param int $stir
     * @return bool
     */
    public function isExistsUserByStir(string $username, int $stir): bool
    {
        return User::query()
            ->where('username', $username)
            ->where('stir', $stir)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->exists();
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function isExistsUserAndPassword(string $username, string $password): bool
    {
        return User::query()
            ->where('username', $username)
            ->where('password', Hash::make($password))
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->exists();
    }

    /**
     * @param string $username
     * @return Builder|User|null
     */
    public function findByUsername(string $username): User|Builder|null
    {
        return User::query()
            ->where('username', $username)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->first();
    }

    /**
     * @param string $username
     * @param int $pin_fl
     * @return Builder|User|null
     */
    public function findByUsernameAndPinFl(string $username, int $pin_fl): User|Builder|null
    {
        return User::query()
            ->where('username', $username)
            ->where('pin_fl', $pin_fl)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->first();
    }

    /**
     * @param string $username
     * @param int $stir
     * @return Builder|User|null
     */
    public function findByUsernameAndStir(string $username, int $stir): User|Builder|null
    {
        return User::query()
            ->where('username', $username)
            ->where('stir', $stir)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->first();
    }

    /**
     * @param int $pin_fl
     * @return Builder|User|null
     */
    public function findByPinFl(int $pin_fl): User|Builder|null
    {
        return User::query()
            ->where('pin_fl', $pin_fl)
            ->first();
    }

    /**
     * @param int $stir
     * @return Builder|User|null
     */
    public function findByStir(int $stir): User|Builder|null
    {
        return User::query()
            ->where('stir', $stir)
            ->first();
    }

    public function getReturnName(int $id): mixed
    {
        return User::query()
            ->where('id', $id)
            ->firstOrFail()->username;
    }
}
