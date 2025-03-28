<?php

namespace App\Core\Service\User;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\User\UserRepository;
use App\Core\Service\User\Contracts\UserContract;
use App\Http\Requests\User\UserRequest;
use App\Models\User;

class UserService implements UserContract
{
    public function __construct(
        protected Transaction    $transaction,
        protected UserRepository $userRepository
    )
    {
    }

    public function create(UserRequest $request): User
    {
        $user = new User();

        $user->fill($request->all());
        $user->setPassword($request->password);

        $this->transaction->wrap(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    public function update(UserRequest $request, int $user_id): User
    {
        $user = $this->userRepository->getById($user_id);

        $user->fill($request->all());
        $user->setPassword($request->password);

        $this->transaction->wrap(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    public function delete(int $user_id): User
    {
        $user = $this->userRepository->getById($user_id);

        $this->transaction->wrap(function () use ($user) {
            $user->delete();
        });

        return $user;
    }

    public function ban(int $user_id): User
    {
        $user = $this->userRepository->getById($user_id);

        $user->ban();
        $this->transaction->wrap(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    public function unban(int $user_id): User
    {
        $user = $this->userRepository->getById($user_id);

        $user->unban();
        $this->transaction->wrap(function () use ($user) {
            $user->save();
        });

        return $user;
    }
}
