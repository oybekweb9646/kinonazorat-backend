<?php

namespace App\Http\Controllers\Request;

use App\Core\Filter\Request\RequestFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\Log\LogRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Service\Request\RequestService;
use App\Http\Requests\Request\RequestRequest;
use App\Http\Requests\Request\ScoreIndicatorRequestPoint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequestController extends Controller
{
    public function __construct(
        public RequestRepository $requestRepository,
        public RequestService    $requestService,
        public LogRepository     $logRepository,
    )
    {
    }

    public function list(): JsonResponse
    {
        return Response::success('All indicators',
            $this->requestRepository->findAll()->toArray() ?? []
        );
    }

    public function filter(RequestFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }


    public function create(RequestRequest $request): JsonResponse
    {
        $requestModel = $this->requestService->create($request);

        return Response::success('Request created', $requestModel->toArray());
    }

    public function update(RequestRequest $request, $id): JsonResponse
    {
        $requestModel = $this->requestService->update($request, $id);

        return Response::success('Request updated', $requestModel->toArray());
    }

    public function delete($id): JsonResponse
    {
        $requestModel = $this->requestService->delete($id);

        return Response::success('Request deleted', $requestModel->toArray());
    }

    public function get($id): JsonResponse
    {
        return Response::success('Request got', $this->requestRepository->getByIdWithIndicators($id)->toArray());
    }

    public function setPoint(ScoreIndicatorRequestPoint $scoreIndicatorRequestPoint, int $id): JsonResponse
    {
        return Response::success('Point set', $this->requestService->setPoint($scoreIndicatorRequestPoint, $id)->toArray());
    }

    public function setFile(ScoreIndicatorRequestPoint $scoreIndicatorRequestPoint, int $id): JsonResponse
    {
        return Response::success('Point set', $this->requestService->setFile($scoreIndicatorRequestPoint, $id)->toArray());
    }

    public function scored(int $id): JsonResponse
    {
        $conclusion = $this->requestService->scored($id);

        return Response::success('Request scored', $conclusion->toArray());
    }

    public function confirm(int $id): JsonResponse
    {
        $conclusion = $this->requestService->confirm($id);

        return Response::success('Request confirmed', $conclusion->toArray());
    }

    public function stat(): JsonResponse
    {
        return Response::success('Stat', [
            'high' => $this->requestRepository->stat(60, 0),
            'normal' => $this->requestRepository->stat(80, 60),
            'danger' => $this->requestRepository->stat(100, 80)
        ]);
    }

    public function generatePdf(int $id)
    {
        $request = $this->requestRepository->getByIdWithRelations($id);
        $pdf = Pdf::loadView('pdf.template', ['request' => $request->toArray()]);

        $fileName = 'pdf/' . Str::random(10) . '.pdf';
        Storage::disk('local')->put($fileName, $pdf->output());
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "document.pdf",
            ["Content-Type" => "application/pdf"]
        );
    }

    public function log(int $id): JsonResponse
    {
        return Response::success('Log', $this->logRepository->findAllByRequest($id)->toArray());
    }
}
