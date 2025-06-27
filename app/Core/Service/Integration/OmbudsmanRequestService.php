<?php

namespace App\Core\Service\Integration;

use App\Core\Dto\HttpClientResponse\FetchAccessTokenOmbudsmanDto;
use App\Core\Dto\HttpClientResponse\FetchRiskAnalysisResultDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

readonly class OmbudsmanRequestService
{
    public function __construct(
        protected string $tokenUrl,
        protected string $serviceUrl,
        protected string $username,
        protected string $password,
    )
    {
    }

    /**
     * @return FetchAccessTokenOmbudsmanDto
     * @throws ConnectionException
     */
    public function fetchAccessToken(): FetchAccessTokenOmbudsmanDto
    {
        $httpResponse = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/json-patch+json',
            ])
            ->post($this->tokenUrl, $this->getFormParamsForAccessToken());

        $this->_throwException($httpResponse);

        return new FetchAccessTokenOmbudsmanDto($httpResponse->json());
    }

    /**
     * @param string $access_token
     * @param $stir
     * @return FetchRiskAnalysisResultDto
     * @throws ConnectionException
     */
    public function sendRiskAnalysisResult(string $accessToken, array $payload): FetchRiskAnalysisResultDto
    {
        $httpResponse = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/json-patch+json',
            ])
            ->post($this->serviceUrl, $payload);

        $this->_throwException($httpResponse);

        if (empty($httpResponse->json()) || !$httpResponse->json('success')) {
            throw new NotFoundResourceException('Ma\'lumotlarni qayta ishlashda xatolik yuz berdi!');
        }

        return new FetchRiskAnalysisResultDto($httpResponse->json());
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
            'username' => $this->username,
            'password' => $this->password
        ];
    }


}
