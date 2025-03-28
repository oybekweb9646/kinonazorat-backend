<?php

namespace App\Core\Service\Auth\Contracts;


use App\Core\Dto\HttpClientResponse\FetchAccessTokenDto;
use App\Core\Dto\HttpClientResponse\FetchUserDetailInfoDto;

interface OneIdContract
{
    public function fetchAccessToken(string $code): FetchAccessTokenDto;

    public function fetchUserDetailInfo(string $access_token): FetchUserDetailInfoDto;

    public function logout(string $access_token): void;
}
