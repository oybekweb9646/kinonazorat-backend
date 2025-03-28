<?php

namespace App\Http\Controllers\Question;

use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Question\QuestionRepository;
use App\Core\Service\Question\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class QuestionAuthorityController extends Controller
{
    public function __construct(
        private readonly QuestionService    $questionService,
    )
    {
    }

    public function read(): JsonResponse
    {
        return Response::success('Question read', $this->questionService->read());
    }
}
