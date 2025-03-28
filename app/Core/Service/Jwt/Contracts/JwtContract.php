<?php

namespace App\Core\Service\Jwt\Contracts;

interface JwtContract
{
    public function validate(string $token);
    public function generateToken();
    public function generateTokenViaUzbToken(string $token);
}
