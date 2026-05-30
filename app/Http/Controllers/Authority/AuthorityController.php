<?php

namespace App\Http\Controllers\Authority;

use App\Core\Filter\Authority\AuthorityFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Service\Authority\AuthorityService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authority\AuthorityFormRequest;
use App\Http\Requests\Authority\AuthorityRequest;
use App\Http\Requests\Integration\StirRequest;
use Illuminate\Http\JsonResponse;

class AuthorityController extends Controller
{
    public function __construct(
        private AuthorityRepository $authorityRepository,
        private AuthorityService    $authorityService,
    )
    {
    }

    public function filter(AuthorityFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }
    public function create(AuthorityFormRequest $request): JsonResponse
    {
        $organization = $this->authorityService->create($request);

        return Response::success('Authority created', $organization->toArray());
    }

    public function update(AuthorityFormRequest $request, int $id): JsonResponse
    {
        $organization = $this->authorityService->update($request, $id);

        return Response::success('Authority updated', (array)$organization->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Authority deleted', (array)$this->authorityService->delete($id)->toArray());
    }

    public function countChecked(): int
    {
        return $this->authorityRepository->countChecked();
    }

    public function countQuestion(): int
    {
        return $this->authorityRepository->countQuestion();
    }

    public function get(int $id)
    {
        return $this->authorityRepository->getById($id);
    }
    public function getByInn(StirRequest $request)
    {
        return $this->authorityService->checkAuthority($request->stir);
    }

    public function excelUpload(AuthorityRequest $request): JsonResponse
    {
        return Response::success('Excel uploaded', $this->authorityService->excelUpload($request));
    }
}
