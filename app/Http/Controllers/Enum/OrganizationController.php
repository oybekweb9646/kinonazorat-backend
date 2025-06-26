<?php

namespace App\Http\Controllers\Enum;

use App\Core\Filter\Organization\OrganizationFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Enum\OrganizationRepository;
use App\Core\Service\Enum\OrganizationService;
use App\Http\Requests\Enum\OrganizationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController
{
    public function __construct(
        private OrganizationRepository $organizationRepository,
        private OrganizationService    $organizationService
    )
    {
    }

    public function list(Request $request): JsonResponse
    {
        return Response::success('All Organizations', $this->organizationRepository->findAll($request)->toArray() ?? []);
    }

    public function filter(OrganizationFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function create(OrganizationRequest $request): JsonResponse
    {
        $organization = $this->organizationService->create($request);

        return Response::success('Organization created', $organization->toArray());
    }

    public function update(OrganizationRequest $request, int $id): JsonResponse
    {
        $organization = $this->organizationService->update($request, $id);

        return Response::success('Organization updated', (array)$organization->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Organization deleted', (array)$this->organizationService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success("Organization", (array)$this->organizationRepository->getById($id));
    }

}
