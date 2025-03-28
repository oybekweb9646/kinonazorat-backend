<?php

namespace App\Core\Service\Auth;

use App\Core\Dto\HttpClientResponse\FetchAccessTokenDto;
use App\Core\Dto\HttpClientResponse\FetchUserDetailInfoDto;
use App\Core\Service\Auth\Contracts\OneIdContract;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OneIdService implements OneIdContract
{
    const string SCHEMA = 'Bearer';

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $authUrl
     * @param string $grantOneAuthorizationCode
     * @param string $grantGetToken
     * @param string $grantAccessTokenIdentify
     * @param string $grantTypeUserLogoutCode
     * @param string|null $code
     */
    public function __construct(
        protected readonly string $clientId,
        protected readonly string $clientSecret,
        protected readonly string $authUrl,
        protected readonly string $grantOneAuthorizationCode,
        protected readonly string $grantGetToken,
        protected readonly string $grantAccessTokenIdentify,
        protected readonly string $grantTypeUserLogoutCode,
        protected ?string         $code = null,
    )
    {
    }

    /**
     * @param string $code
     * @return FetchAccessTokenDto
     * @throws ConnectionException
     */
    public function fetchAccessToken(string $code): FetchAccessTokenDto
    {
        $this->setCode($code);

        $httpResponse = Http::asForm()
            ->post($this->getAuthUrl(), $this->getFormParamsForAccessToken());

        $this->_throwException($httpResponse);

        return new FetchAccessTokenDto($httpResponse->json());
    }

    /**
     * @param string $access_token
     * @return FetchUserDetailInfoDto
     * @throws ConnectionException
     */
    public function fetchUserDetailInfo(string $access_token): FetchUserDetailInfoDto
    {
        $httpResponse = Http::asForm()
            ->post($this->getAuthUrl(), $this->getFormParamsForUserDetailInfo($access_token));

        $this->_throwException($httpResponse);

        return new FetchUserDetailInfoDto($httpResponse->json());
    }

    /**
     * @param string $access_token
     * @return void
     * @throws ConnectionException
     */
    public function logout(string $access_token): void
    {
        $httpResponse = Http::asForm()
            ->post($this->getAuthUrl(), $this->getFormParamsForUserLogout($access_token));

        $this->_throwException($httpResponse);
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

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getAuthUrl(): string
    {
        return $this->authUrl;
    }

    public function getGrantOneAuthorizationCode(): string
    {
        return $this->grantOneAuthorizationCode;
    }

    public function getGrantGetToken(): string
    {
        return $this->grantGetToken;
    }

    public function getGrantAccessTokenIdentify(): string
    {
        return $this->grantAccessTokenIdentify;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getGrantTypeUserLogoutCode(): string
    {
        return $this->grantTypeUserLogoutCode;
    }

    /**
     * @return array
     */
    public function getFormParamsForAccessToken(): array
    {
        return [
            'code'          => $this->getCode(),
            'grant_type'    => $this->getGrantOneAuthorizationCode(),
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret()
        ];
    }

    /**
     * @param string $access_token
     * @return array
     */
    public function getFormParamsForUserDetailInfo(string $access_token): array
    {
        return [
            'grant_type'    => $this->getGrantAccessTokenIdentify(),
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'access_token'  => $access_token
        ];
    }

    /**
     * @param string $access_token
     * @return array
     */
    protected function getFormParamsForUserLogout(string $access_token): array
    {
        return [
            'grant_type'    => $this->getGrantTypeUserLogoutCode(),
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'access_token'  => $access_token,
            'scope'         => $this->getClientId()
        ];
    }
}
