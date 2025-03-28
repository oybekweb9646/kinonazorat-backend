<?php

namespace App\Http\Controllers\Enum;

use App\Core\Filter\Indicator\IndicatorFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Enum\IndicatorRepository;
use App\Core\Service\Enum\IndicatorService;
use App\Http\Requests\Enum\IndicatorRequest;
use App\Http\Requests\ScoresRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndicatorController
{
    public function __construct(
        private IndicatorRepository $indicatorRepository,
        private IndicatorService    $indicatorService
    )
    {
    }

    public function list(Request $request): JsonResponse
    {
        return Response::success('All indicators', $this->indicatorRepository->findAll($request)->toArray() ?? []);
    }

    public function filter(IndicatorFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function create(IndicatorRequest $request): JsonResponse
    {
        $indicator = $this->indicatorService->create($request);

        return Response::success('Indicator created', $indicator->toArray());
    }

    public function update(IndicatorRequest $request, int $id): JsonResponse
    {
        $indicator = $this->indicatorService->update($request, $id);

        return Response::success('Indicator updated', (array)$indicator->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Indicator deleted', (array)$this->indicatorService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success("Indicator", (array)$this->indicatorRepository->getById($id));
    }

    public function scores(ScoresRequest $request): JsonResponse
    {
        $this->indicatorService->saveScores($request);
        return Response::success();
    }
}
