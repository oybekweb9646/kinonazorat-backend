<?php

namespace App\Core\Service\User\Contracts;

use App\Http\Requests\User\UserRequest;
use App\Models\User;

interface UserContract
{
    public function create(UserRequest $request): User;
}
