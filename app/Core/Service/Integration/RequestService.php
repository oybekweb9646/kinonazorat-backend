<?php

namespace App\Core\Service\Integration;

use App\Core\Dto\HttpClientResponse\FetchAccessTokenMibDto;
use App\Core\Dto\HttpClientResponse\FetchAuthorityDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

readonly class RequestService
{
    public function __construct(
        protected string $tokenUrl,
        protected string $serviceUrl,
        protected string $grantType,
        protected string $username,
        protected string $password,
        protected string $key,
        protected string $secret
    )
    {
    }

    /**
     * @param string $code
     * @return FetchAccessTokenMibDto
     * @throws ConnectionException
     */
    public function fetchAccessToken(): FetchAccessTokenMibDto
    {
        $httpResponse = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->key . ':' . $this->secret),
            ])
            ->post($this->tokenUrl, $this->getFormParamsForAccessToken());

        $this->_throwException($httpResponse);

        return new FetchAccessTokenMibDto($httpResponse->json());
    }

    /**
     * @param string $access_token
     * @param $stir
     * @return FetchAuthorityDto
     * @throws ConnectionException
     */
    public function fetchAuthorityInfo(string $access_token, $stir)
    {
        $httpResponse = Http::withToken($access_token)
            ->post($this->serviceUrl, $this->getFormParamsForAuthority($stir));

        $this->_throwException($httpResponse);
        if (empty($httpResponse->json())) {
            throw new NotFoundResourceException('Bunday tashkilot mavjud emas.');
        }

        return new FetchAuthorityDto($httpResponse->json());
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

            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getFormParamsForAccessToken(): array
    {
        return [
            'grant_type' => $this->grantType,
            'username' => $this->username,
            'password' => $this->password
        ];
    }


    /**
     * @return array
     */
    public function getFormParamsForAuthority($stir): array
    {
        return [
            'tin' => $stir
        ];
    }
}
