<?php

namespace App\Core\Dto\HttpClientResponse;


class FetchAccessTokenMibDto
{
    public function __construct(
        protected array   $response {
            get {
                return $this->response;
            }
        },
        protected ?string $scope = null {
            get {
                return $this->scope;
            }
            set {
                $this->scope = $value;
            }
        },
        protected ?string $token_type = null {
            get {
                return $this->token_type;
            }
            set {
                $this->token_type = $value;
            }
        },
        protected ?string $refresh_token = null {
            get {
                return $this->refresh_token;
            }
            set {
                $this->refresh_token = $value;
            }
        },
        public ?string $access_token = null {
            get {
                return $this->access_token;
            }
            set {
                $this->access_token = $value;
            }
        }
    )
    {
        foreach ($this->response as $key => $value) {
            if (property_exists(self::class, $key)) {
                $this->{$key} = $value;
            }
        }
    }

}
