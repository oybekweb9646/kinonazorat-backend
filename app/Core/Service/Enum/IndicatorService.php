<?php

namespace App\Core\Service\Enum;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Enum\IndicatorRepository;
use App\Http\Requests\Enum\IndicatorRequest;
use App\Http\Requests\ScoresRequest;
use App\Models\Indicator;

class IndicatorService
{
    public function __construct(
        private readonly Transaction         $transaction,
        private readonly IndicatorRepository $indicatorRepository
    )
    {
    }

    public function create(IndicatorRequest $request): Indicator
    {
        $indicator = new Indicator();

        $indicator->fill($request->all());

        $this->transaction->wrap(function () use ($indicator) {
            $indicator->save();
        });

        return $indicator;
    }

    public function update(IndicatorRequest $request, int $user_id): Indicator
    {
        $indicator = $this->indicatorRepository->getByIdObject($user_id);

        $indicator->fill($request->all());

        $this->transaction->wrap(function () use ($indicator) {
            $indicator->save();
        });

        return $indicator;
    }

    public function delete(int $id): Indicator
    {
        $indicator = $this->indicatorRepository->getByIdObject($id);

        $this->transaction->wrap(function () use ($indicator) {
            $indicator->delete();
        });

        return $indicator;
    }

    public function saveScores(ScoresRequest $request): void
    {
        $this->transaction->wrap(function () use ($request) {
            foreach ($request['indicators'] as $indicator) {
                $ind = $this->indicatorRepository->getByIdObject($indicator['id']);
                $ind->max_score = $indicator['max_score'];
                $ind->save();
            }
        });
    }
}
