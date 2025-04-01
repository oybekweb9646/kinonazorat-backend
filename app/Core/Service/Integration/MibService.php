<?php

namespace App\Core\Service\Integration;

use App\Core\Enums\Role\RoleEnum;
use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\Enum\SoatoRegionsRepository;
use App\Models\Authority;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

readonly class MibService
{
    public function __construct(
        protected Transaction            $transaction,
        protected AuthorityRepository    $authorityRepository,
        protected RequestService         $requestService,
        protected SoatoRegionsRepository $soatoRegionsRepository
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function checkAuthority($stir)
    {
        $fetAccessTokenDto = $this->requestService->fetchAccessToken();

        $authorityInfo = $this->requestService->fetchAuthorityInfo($fetAccessTokenDto->access_token, $stir);
        $authority = $this->authorityRepository->findByStir($stir);

        if (is_null($authority)) {
            $authority = new Authority();
        }
        $user = auth()->user();
        $soato = $this->soatoRegionsRepository->findById($authorityInfo->billing_soato);

        if ($user->role == RoleEnum::_TERRITORIAL_RESPONSIBLE) {
            if (!empty($soato)) {
                if ($user->getRegionId() != $soato->id || $user->getRegionId() != $soato->parent_id) {
                    throw new NotFoundResourceException('Ushbu tadbirkorlik subyekti sizning hududingizga tegishli emas !!!');
                }
            } else {
                throw new NotFoundResourceException('Ushbu tadbirkorlik subyektining hududi belgilanmagan !!!');
            }
        }

        $authority->fill([
            'stir' => $authorityInfo->stir,
            'name_uz' => $authorityInfo->name,
            'name_ru' => $authorityInfo->name,
            'name_uzc' => $authorityInfo->name,
            'billing_address' => $authorityInfo->billing_address ?? null,
            'billing_soato' => $authorityInfo->billing_soato ?? null,
            'director_address' => $authorityInfo->director_address ?? null,
            'director_soato' => $authorityInfo->director_soato ?? null,
            'director_lastName' => $authorityInfo->director_lastName ?? null,
            'director_middleName' => $authorityInfo->director_middleName ?? null,
            'director_firstName' => $authorityInfo->director_firstName ?? null,
            'director_gender' => $authorityInfo->director_gender ?? null,
            'director_nationality' => $authorityInfo->director_nationality ?? null,
            'director_citizenship' => $authorityInfo->director_citizenship ?? null,
            'director_passportNumber' => $authorityInfo->director_passportNumber ?? null,
            'director_stir' => $authorityInfo->director_stir ?? null,
            'director_pinfl' => $authorityInfo->director_pinfl ?? null,
            'director_countryCode' => $authorityInfo->director_countryCode ?? null,
            'director_passportSeries' => $authorityInfo->director_passportSeries ?? null,
        ]);

        $this->transaction->wrap(function () use ($authority) {
            $authority->save();
        });

        return $authority;
    }
}
