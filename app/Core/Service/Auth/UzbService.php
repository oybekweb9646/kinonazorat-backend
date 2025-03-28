<?php

namespace App\Core\Service\Auth;

use App\Core\Dto\Auth\AccessAuthDto;
use App\Core\Enums\Auth\AccessAuthEnum;
use App\Core\Service\Auth\Contracts\UzbContract;
use App\Core\Service\Jwt\JwtService;
use App\Exceptions\ForbiddenAccessException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UzbService implements UzbContract
{
    public function __construct(
        protected JwtService $jwtService
    )
    {
    }


    /**
     * @param string $token
     * @return void
     */
    public function validateToken(string $token): void
    {
        if (!$this->jwtService->validate($token)) {
            throw new \DomainException(__('client.Jwt token is not match'));
        }
    }
    /**
     * @param Response $response
     * @return void
     */
    protected function _throwException(Response $response): void
    {
        if (!$response->successful()) {
            $exception = $response->toException();

            if (is_null($exception)) {
                throw new BadRequestHttpException(__('client.Unknown error'));
            }

            throw new \DomainException($exception->getMessage());
        }
    }
}
