<?php

namespace App\Core\Service\Enum;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Enum\OrganizationRepository;
use App\Http\Requests\Enum\OrganizationRequest;
use App\Models\Organization;

class OrganizationService
{
    public function __construct(
        private readonly Transaction         $transaction,
        private readonly OrganizationRepository $organizationRepository
    )
    {
    }

    public function create(OrganizationRequest $request): Organization
    {
        $organization = new Organization();

        $organization->fill($request->all());

        $this->transaction->wrap(function () use ($organization) {
            $organization->save();
        });

        return $organization;
    }

    public function update(OrganizationRequest $request, int $id): Organization
    {
        $organization = $this->organizationRepository->getByIdObject($id);

        $organization->fill($request->all());

        $this->transaction->wrap(function () use ($organization) {
            $organization->save();
        });

        return $organization;
    }

    public function delete(int $id): Organization
    {
        $organization = $this->organizationRepository->getByIdObject($id);

        $this->transaction->wrap(function () use ($organization) {
            $organization->delete();
        });

        return $organization;
    }
}
