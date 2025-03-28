<?php

namespace App\Http\Controllers\Enum;

use App\Core\Filter\IndicatorType\IndicatorTypeFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Enum\IndicatorTypeRepository;
use App\Core\Service\Enum\IndicatorTypeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Enum\IndicatorTypeRequest;
use Illuminate\Http\JsonResponse;

class IndicatorTypeController extends Controller
{
    public function __construct(
        private IndicatorTypeRepository $indicatorTypeRepository,
        private IndicatorTypeService    $indicatorTypeService
    )
    {
    }

    public function list(): JsonResponse
    {
        return Response::success('All indicator types', $this->indicatorTypeRepository->findAll()->toArray() ?? []);
    }

    public function filter(IndicatorTypeFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function create(IndicatorTypeRequest $request): JsonResponse
    {
        $indicatorType = $this->indicatorTypeService->create($request);

        return Response::success('Indicator Type created', (array)$indicatorType->toArray());
    }

    public function update(IndicatorTypeRequest $request, int $id): JsonResponse
    {
        $indicatorType = $this->indicatorTypeService->update($request, $id);

        return Response::success('Indicator Type updated', (array)$indicatorType->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Indicator Type deleted', (array)$this->indicatorTypeService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success('Indicator Type', (array)$this->indicatorTypeRepository->getById($id)->toArray());
    }
}
