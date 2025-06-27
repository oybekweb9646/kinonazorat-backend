<?php

namespace App\Core\Service\Integration;

use App\Core\Dto\HttpClientResponse\FetchRiskAnalysisResultDto;
use App\Core\Enums\Role\RoleEnum;
use App\Core\Helpers\DB\Transaction;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\Request\RequestRepository;
use App\Models\Authority;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

readonly class OmbudsmanService
{
    public function __construct(
        protected Transaction             $transaction,
        protected OmbudsmanRequestService $requestService,
        protected RequestRepository       $requestRepository,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function sendData($sendingData): FetchRiskAnalysisResultDto
    {
        $fetAccessTokenDto = $this->requestService->fetchAccessToken();
        return $this->requestService->sendRiskAnalysisResult($fetAccessTokenDto->token, $sendingData);
    }
}
