<?php

namespace App\Core\Service\Auth\Contracts;

use App\Core\Dto\Auth\JwtTokenDto;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OneIdCodeRequest;
use Illuminate\Http\Request;

interface AuthContract
{
    public function loginByOneId(OneIdCodeRequest $oneIdCodeRequest): JwtTokenDto;

    public function loginByUser(LoginRequest $loginRequest): JwtTokenDto;

    public function refreshToken(Request $request): JwtTokenDto;

    public function logout(): void;

    public function parseAuthHeader(Request $request): string;
}