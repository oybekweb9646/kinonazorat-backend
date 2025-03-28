<?php

namespace App\Core\Dto\HttpClientResponse;


class FetchAccessTokenDto
{
    public function __construct(
        protected array   $response,
        protected ?string $scope = null,
        protected ?string $expires_in = null,
        protected ?string $token_type = null,
        protected ?string $refresh_token = null,
        protected ?string $access_token = null
    )
    {
        foreach ($this->response as $key => $value) {
            if (property_exists(self::class, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getResponse(): array
    {
        return $this->response;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }

    public function getExpiresIn(): ?string
    {
        return $this->expires_in;
    }

    public function setExpiresIn(?string $expires_in): void
    {
        $this->expires_in = $expires_in;
    }

    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    public function setTokenType(?string $token_type): void
    {
        $this->token_type = $token_type;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(?string $refresh_token): void
    {
        $this->refresh_token = $refresh_token;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token): void
    {
        $this->access_token = $access_token;
    }
}
