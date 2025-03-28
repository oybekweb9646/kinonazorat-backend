<?php

namespace App\Http\Controllers\Checklist;

use App\Core\Helpers\Responses\Response;
use App\Core\Repository\checklist\ChecklistRepository;
use App\Core\Service\Checklist\ChecklistService;
use App\Http\Requests\Checklist\CheckedRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ChecklistAuthorityController extends Controller
{
    public function __construct(
        private readonly ChecklistService    $checklistService,
        private readonly ChecklistRepository $checklistRepository
    )
    {
    }

    public function check(CheckedRequest $request, $id): JsonResponse
    {
        $checklist = $this->checklistRepository->getByIdObject($id);

        return Response::success('Checklist check', $this->checklistService->check($checklist, $request));
    }

    public function confirm(int $id): JsonResponse
    {
        return Response::success('Checklist confirm', $this->checklistService->confirm($id));
    }
}
