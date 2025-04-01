<?php

namespace App\Http\Controllers\Enum;

use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Enum\SoatoRegionsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SoatoRegionController extends Controller
{
    public function __construct(
        private SoatoRegionsRepository $soatoRegionsRepository
    )
    {
    }

    public function list(): JsonResponse
    {
        return Response::success('All soato regions', $this->soatoRegionsRepository->findParentAll()->toArray() ?? []);
    }
}
