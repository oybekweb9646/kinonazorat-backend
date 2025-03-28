<?php

namespace App\Http\Controllers\Checklist;

use App\Core\Filter\Checklist\ChecklistFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\checklist\ChecklistRepository;
use App\Core\Service\Checklist\ChecklistService;
use App\Http\Requests\Checklist\ChecklistRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ChecklistController extends Controller
{
    public function __construct(
        private readonly ChecklistRepository $checklistRepository,
        private readonly ChecklistService    $checklistService
    )
    {
    }

    public function filter(ChecklistFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function list($id): JsonResponse
    {
        return Response::success('Checklist list', $this->checklistRepository->findAllForAuthority($id));
    }

    public function create(ChecklistRequest $request): JsonResponse
    {
        $checklist = $this->checklistService->create($request);

        return Response::success('Checklist created', $checklist->toArray());
    }

    public function update(ChecklistRequest $request, int $id): JsonResponse
    {
        $checklist = $this->checklistService->update($request, $id);

        return Response::success('Checklist updated', $checklist->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Checklist deleted', $this->checklistService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success("Checklist", $this->checklistRepository->getById($id));
    }

}
