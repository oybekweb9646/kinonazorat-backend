<?php

namespace App\Core\Repository\Request;

use App\Core\Enums\Request\State;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class RequestRepository
{
    public function findAll(): Collection
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'createdBy'
            ])
            ->get();
    }

    public function getById(int $id)
    {
        return Request::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getByIdWithRelations(int $id)
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->where('id', $id)
            ->with([
                'indicatorType' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name']);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name', '*']);
                },
                'createdBy'
            ])
            ->firstOrFail();
    }


    public function getByIdWithIndicators(int $id)
    {
        $name = LanguageHelper::getName();
        return Request::query()
            ->where('id', $id)
            ->with([
                'scoreRequestIndicators' => function ($query) use ($name) {
                    $query->with([
                        'indicator' => function ($query) use ($name) {
                            $query->select(['id', $name . ' as name']);
                        },
                        'linkScoreIndicatorFiles' => function ($query) {
                            $query->with([
                                'file' => function ($query) {
                                    return $query->select(['id', "original_name AS name", "path"]);
                                }
                            ]);
                        },
                        'updatedBy' => function ($query) {
                            $query->select(['id', 'username']);
                        },
                    ]);
                },
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name', '*']);
                }
            ])
            ->firstOrFail();
    }

    public function stat($max, $min): int
    {
        return Request::query()
            ->where('score', '>=', $min)
            ->where('score', '<=', $max)
            ->count();
    }

    public function findNoConfirmed(int $authorityId, ?int $indicatorTypeId = null)
    {
        $query = Request::query()
            ->where('authority_id', $authorityId)
            ->where('status', '<', State::SEND_FOR_INSECTION->value);

        $query->when($indicatorTypeId, function ($query) use ($indicatorTypeId) {
            return $query->where('indicator_type_id', $indicatorTypeId);
        });

        return $query->first();
    }

    public function buildRiskAnalysisPayload(Request $request): array
    {
        return [
            'externalId' => $request->request_no,
            'inspectorInn' => $request->createdBy?->organization?->inn,
            'inspector' => $request->createdBy?->full_name,
            'createdAt' => Carbon::parse($request->created_at)->format('Y-m-d H:i:s'),
            'contractorInn' => $request->authority?->stir,
            'orderNum' => $request->order_number ?? null,
            'orderInspector' => $request->order_inspector ?? null,
            'totalBall' => $request->score,
            'url' => 'aokatahlil.uz',
            'indicators' => optional($request->scoreRequestIndicators)->map(function ($indicator) {
                    return [
                        'externalId' => (string)$indicator->id,
                        'name' => $indicator->indicator?->{LanguageHelper::getName()},
                        'ball' => $indicator->score,
                        'translations' => [
                            [
                                'languageCode' => 'uz-cyrl',
                                'text' => $indicator->indicator?->name_uzc,
                            ],
                            [
                                'languageCode' => 'uz-latn',
                                'text' => $indicator->indicator?->name_uz,
                            ],
                            [
                                'languageCode' => 'ru',
                                'text' => $indicator->indicator?->name_ru,
                            ]
                        ]
                    ];
                })->toArray() ?? []

        ];
    }

}
