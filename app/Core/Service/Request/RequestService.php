<?php

namespace App\Core\Service\Request;

use App\Core\Enums\Request\State;
use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\Enum\IndicatorRepository;
use App\Core\Repository\link\LinkScoreIndicatorFilesRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Repository\Request\ScoreRequestIndicatorRepository;
use App\Core\Service\Log\Request\RequestLogService;
use App\Core\Service\Log\ScoreRequestIndicator\ScoreRequestIndicatorLogService;
use App\Http\Requests\Request\RequestRequest;
use App\Models\LinkScoreRequestIndicatorFiles;
use App\Models\Request;
use App\Models\ScoreRequestIndicator;

class RequestService
{
    public function __construct(
        public Transaction                       $transaction,
        public IndicatorRepository               $indicatorRepository,
        public RequestRepository                 $requestRepository,
        public ScoreRequestIndicatorRepository   $scoreRequestIndicatorRepository,
        public AuthorityRepository               $authorityRepository,
        public RequestLogService                 $requestLogService,
        public ScoreRequestIndicatorLogService   $scoreRequestIndicatorLogService,
        public LinkScoreIndicatorFilesRepository $linkFileRepository,

    )
    {
    }


    public function create(RequestRequest $request): Request
    {
        if (!empty($requestModel = $this->requestRepository->findNoConfirmed($request->authority_id, $request->indicator_type_id))) {
            return $requestModel;
        } else {
            $requestModel = new Request();
            $requestModel->fill($request->all());
            $authority = $this->authorityRepository->getById($requestModel->authority_id);
            $requestModel->stir = $authority->stir;
        }
        $indicators = $this->indicatorRepository->getByIndicatorTypeId($request->indicator_type_id);


        $this->transaction->wrap(function () use ($requestModel, $indicators, $request) {
            $requestModel->save();

            foreach ($indicators as $indicator) {
                $scoreIndicatorRequest = new ScoreRequestIndicator();
                $scoreIndicatorRequest->request_id = $requestModel->id;
                $scoreIndicatorRequest->indicator_id = $indicator->id;
                $scoreIndicatorRequest->max_score = $indicator->max_score;
                $scoreIndicatorRequest->save();
            }

            $this->requestLogService->created($requestModel, $request->ip(), $request->userAgent());
        });

        return $requestModel;
    }

    public function update(RequestRequest $request, $id): Request
    {
        $requestModel = $this->requestRepository->getById($id);
        $authority = $this->authorityRepository->getById($requestModel->authority_id);
        $requestModel->stir = $authority->stir;
        $requestModel->created_by = auth()->user()->id;
        $requestModel->fill($request->all());

        $this->transaction->wrap(function () use ($requestModel, $request, $id) {
            $requestModel->save();

            $scoreIndicatorRequests = $this->scoreRequestIndicatorRepository->getByRequestId($id);
            foreach ($scoreIndicatorRequests as $scoreIndicatorRequest) {
                $scoreIndicatorRequest->delete();
            }

            $indicators = $this->indicatorRepository->getByIndicatorTypeId($request->indicator_type_id);
            foreach ($indicators as $indicator) {
                $scoreIndicatorRequest = new ScoreRequestIndicator();
                $scoreIndicatorRequest->request_id = $id;
                $scoreIndicatorRequest->indicator_id = $indicator->id;
                $scoreIndicatorRequest->max_score = $indicator->max_score;
                $scoreIndicatorRequest->save();

            }
        });

        return $requestModel;
    }

    public function delete(int $id): Request
    {
        $requestModel = $this->requestRepository->getById($id);
        $this->transaction->wrap(function () use ($requestModel) {

            $scoreIndicatorRequests = $this->scoreRequestIndicatorRepository->getByRequestId($requestModel->id);
            foreach ($scoreIndicatorRequests as $scoreIndicatorRequest) {
                $scoreIndicatorRequest->delete();
            }

            $requestModel->delete();

            $this->requestLogService->deleted($requestModel);
        });

        return $requestModel;
    }

    public function setPoint($request, int $id): ScoreRequestIndicator
    {
        $scoreIndicatorRequest = $this->scoreRequestIndicatorRepository->getById($id);

        $attributes = $scoreIndicatorRequest->getAttributes();
        $requestModel = $this->requestRepository->getById($scoreIndicatorRequest->request_id);
        if ($request->score) {
            $scoreIndicatorRequest->setPoint();
        } else {
            $scoreIndicatorRequest->removePoint();
        }

        $requestModel->status = State::PARTLY_SCORED;
        $this->transaction->wrap(function () use ($scoreIndicatorRequest, $requestModel, $attributes) {
            $scoreIndicatorRequest->save();
            $requestModel->save();

            $this->scoreRequestIndicatorLogService->scoreChanged($scoreIndicatorRequest, $attributes);
        });

        return $scoreIndicatorRequest;
    }

    public function scored(int $id): Request
    {
        $requestModel = $this->requestRepository->getById($id);

        $attributes = $requestModel->getAttributes();
        $requestModel->status = State::SCORED;
        $requestModel->closed_at = now()->format('Y-m-d');

        $this->transaction->wrap(function () use ($requestModel, $attributes) {
            $requestModel->save();

            $this->requestLogService->scored($requestModel, $attributes);
        });

        return $requestModel;
    }

    public function confirm(int $id): Request
    {
        $requestModel = $this->requestRepository->getById($id);
        $requestModel->status = State::CONFIRMED;
        $requestModel->closed_at = now();

        $this->transaction->wrap(function () use ($requestModel) {
            $requestModel->save();
        });

        return $requestModel;
    }

    public function setFile($request, int $id): ScoreRequestIndicator
    {
        $scoreIndicatorRequest = $this->scoreRequestIndicatorRepository->getById($id);

        $this->transaction->wrap(function () use ($request, $scoreIndicatorRequest) {

            if ($request->file_id) {
                $link = LinkScoreRequestIndicatorFiles::create([
                    'score_request_indicator_id' => $scoreIndicatorRequest->id,
                    'file_id' => $request->file_id,
                ]);
            }

            if (!empty($link)) {
                $this->scoreRequestIndicatorLogService->setLinkFile($link, $link->getAttributes());
            }
        });

        return $scoreIndicatorRequest;
    }
}
