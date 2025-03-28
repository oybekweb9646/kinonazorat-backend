<?php

namespace App\Providers;

use App\Core\Repository\User\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class CustomAuthProvider implements UserProvider
{
    /**
     * @param $identifier
     * @return User|Authenticatable|Builder|null
     */
    public function retrieveById($identifier): User|Builder|Authenticatable|null
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = App::make(UserRepository::class);

        return $userRepository->findById($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {

    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * @param array $credentials
     * @return User|Authenticatable|Builder|null
     */
    public function retrieveByCredentials(array $credentials): User|Builder|Authenticatable|null
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = App::make(UserRepository::class);

        if (!empty($credentials['pin_fl'])) {
            return $userRepository->findByUsernameAndPinFl($credentials['username'], $credentials['pin_fl']);
        } elseif (!empty($credentials['stir'])) {
            return $userRepository->findByUsernameAndStir($credentials['username'], $credentials['stir']);
        } elseif (!empty($credentials['password'])) {
            /**
             * @var User|null $user
             */
            $user = $userRepository->findByUsername($credentials['username']);

            if (empty($user)) {
                return null;
            }

            if ($user->isEmptyPassword() && $user->isNotEmptyPinFl() && $user->isActive()) {
                return $user;
            }

            return $user->isCheckPassword($credentials['password']) ? $user : null;
        }

        return null;
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = App::make(UserRepository::class);

        if (!empty($credentials['pin_fl'])) {
            return $userRepository->isExistsUser($credentials['username'], $credentials['pin_fl']);
        } elseif (!empty($credentials['stir'])) {
            return $userRepository->isExistsUserByStir($credentials['username'], $credentials['stir']);
        } elseif (!empty($credentials['password'])) {
            $user = $userRepository->findByUsername($credentials['username']);

            if (empty($user)) {
                return false;
            }

            if ($user->isEmptyPassword() && $user->isNotEmptyPinFl() && $user->isActive()) {
                return true;
            }

            return $user->isCheckPassword($credentials['password']);
        }

        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false)
    {
        // TODO: Implement rehashPasswordIfRequired() method.
    }
}
