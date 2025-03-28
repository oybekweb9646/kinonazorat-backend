<?php

namespace App\Core\Dto\Auth;

class JwtTokenDto
{
    public function __construct(
        protected string $access_token
    )
    {
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }
}
