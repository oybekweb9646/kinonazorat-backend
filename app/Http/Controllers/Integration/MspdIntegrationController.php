<?php

namespace App\Http\Controllers\Integration;

use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Integration\IntegrationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * MSPD (va boshqa tashqi tizimlar) uchun faqat o'qish (GET) integratsiya endpointlari.
 */
class MspdIntegrationController extends Controller
{
    public function __construct(
        public IntegrationRepository $repository,
    ) {
    }

    /**
     * Yakunlangan risk natijalari ro'yxati (paginatsiya + filtrlar).
     * GET /api/integration/risk-results
     */
    public function riskResults(Request $request): JsonResponse
    {
        return Response::success(
            'Risk results',
            $this->repository->riskResults($request->query())
        );
    }

    /**
     * Berilgan STIR bo'yicha subyekt va uning risk natijalari (indikatorlar bilan).
     * GET /api/integration/risk-results/by-stir/{stir}
     */
    public function riskResultByStir(int $stir): JsonResponse
    {
        $data = $this->repository->riskResultByStir($stir);

        if ($data === null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Organization with given STIR not found',
                'data' => [],
            ], 404);
        }

        return Response::success('Risk result by stir', $data);
    }
}
