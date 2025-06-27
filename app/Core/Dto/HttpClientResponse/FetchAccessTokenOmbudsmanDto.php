<?php

namespace App\Core\Dto\HttpClientResponse;


class FetchAccessTokenOmbudsmanDto
{
    public string $token;
    public string $expireOn;

    public function __construct(array $response)
    {
        $this->token = $response['token'];
        $this->expireOn = $response['expireOn'];
    }

}
