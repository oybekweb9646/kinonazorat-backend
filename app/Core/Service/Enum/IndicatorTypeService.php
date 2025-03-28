<?php

namespace App\Core\Service\Enum;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Enum\IndicatorTypeRepository;
use App\Http\Requests\Enum\IndicatorTypeRequest;
use App\Models\IndicatorType;

class IndicatorTypeService
{
    public function __construct(
        private readonly Transaction             $transaction,
        private readonly IndicatorTypeRepository $indicatorTypeRepository
    )
    {
    }

    public function create(IndicatorTypeRequest $request): IndicatorType
    {
        $indicatorType = new IndicatorType();

        $indicatorType->fill($request->all());

        $this->transaction->wrap(function () use ($indicatorType) {
            $indicatorType->save();
        });

        return $indicatorType;
    }

    public function update(IndicatorTypeRequest $request, int $user_id): IndicatorType
    {
        $indicatorType = $this->indicatorTypeRepository->getById($user_id);

        $indicatorType->fill($request->all());

        $this->transaction->wrap(function () use ($indicatorType) {
            $indicatorType->save();
        });

        return $indicatorType;
    }

    public function delete(int $id): IndicatorType
    {
        $indicatorType = $this->indicatorTypeRepository->getById($id);

        $this->transaction->wrap(function () use ($indicatorType) {
            $indicatorType->delete();
        });

        return $indicatorType;
    }
}
