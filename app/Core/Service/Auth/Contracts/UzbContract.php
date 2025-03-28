<?php

namespace App\Core\Service\Auth\Contracts;

use Illuminate\Http\Client\Response;

interface UzbContract
{
    public function fetchAccessToken(string $pin_fl);

    public function fetchUserDetailInfoFromPM(string $access_token);

    public function fetchAccessTokenByUser(string $username, string $password);

    public function validateToken(string $token);
}

