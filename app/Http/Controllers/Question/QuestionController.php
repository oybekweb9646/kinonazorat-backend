<?php

namespace App\Http\Controllers\Question;

use App\Core\Filter\Question\QuestionFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Question\QuestionRepository;
use App\Core\Service\Question\QuestionService;
use App\Http\Requests\Question\QuestionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class QuestionController extends Controller
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly QuestionService    $questionService
    )
    {
    }

    public function filter(QuestionFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function list(): JsonResponse
    {
        return Response::success('Checklist list', $this->questionRepository->findAllForAuthority());
    }

    public function create(QuestionRequest $request): JsonResponse
    {
        $indicator = $this->questionService->create($request);

        return Response::success('Question created', $indicator->toArray());
    }

    public function update(QuestionRequest $request, int $id): JsonResponse
    {
        $indicator = $this->questionService->update($request, $id);

        return Response::success('Question updated', $indicator->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('Question deleted', $this->questionService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success("Question", $this->questionRepository->getById($id));
    }

}
