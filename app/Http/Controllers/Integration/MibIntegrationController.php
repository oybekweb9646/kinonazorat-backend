<?php

namespace App\Http\Controllers\Integration;

use App\Core\Helpers\Responses\Response;
use App\Core\Service\Integration\MibService;
use App\Http\Requests\Integration\StirRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class MibIntegrationController extends Controller
{
    public function __construct(
        private readonly MibService $mibService,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function get(StirRequest $request): JsonResponse
    {
        return Response::success('Authority got', $this->mibService->checkAuthority($request->stir));
    }
}
