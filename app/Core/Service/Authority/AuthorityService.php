<?php

namespace App\Core\Service\Authority;

use App\Core\Helpers\DB\Transaction;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Repository\User\UserRepository;
use App\Http\Requests\Authority\AuthorityFormRequest;
use App\Http\Requests\Authority\AuthorityRequest;
use App\Models\Authority;
use App\Models\Organization;
use App\Models\User;

class AuthorityService
{
    public function __construct(
        private AuthorityRepository $authorityRepository,
        private UserRepository      $userRepository,
        private Transaction         $transaction,
        private RequestRepository   $requestRepository,
    )
    {
    }

    public function excelUpload(AuthorityRequest $request): bool
    {
        $this->transaction->wrap(function () use ($request) {
            foreach ($request->data as $form) {
                $authority = $this->authorityRepository->findByStir($form['authority_inn']);
                if (!$authority) {
                    $authority = new Authority();
                }
                $authority->stir = $form['authority_inn'];
                $authority->address = $form['authority_address'];
                $authority->billing_address = $form['authority_address'];
                $authority->name_uz = $form['authority_name'];
                $authority->save();
                $user = $this->userRepository->findByStir($form['authority_inn']);
                if (!$user) {
                    $user = new User();
                }
                $user->username = $form['authority_name'];
                $user->stir = $form['authority_inn'];
                $user->save();
            }
        });

        return true;
    }


    public function create(AuthorityFormRequest $request): Authority
    {
        $organization = new Authority();

        $organization->fill($request->all());

        $this->transaction->wrap(function () use ($organization) {
            $organization->save();
        });

        return $organization;
    }

    public function update(AuthorityFormRequest $request, int $id): Authority
    {
        $organization = $this->authorityRepository->getByIdObject($id);

        $organization->fill($request->all());

        $this->transaction->wrap(function () use ($organization) {
            $organization->save();
        });

        return $organization;
    }

    public function delete(int $id): Authority
    {
        $organization = $this->authorityRepository->getByIdObject($id);

        $this->transaction->wrap(function () use ($organization) {
            $organization->delete();
        });

        return $organization;
    }

    /**
     * @throws ConnectionException
     */
    public function checkAuthority($stir): array
    {
        $authority = $this->authorityRepository->getByStir($stir);
        $request = $this->requestRepository->findNoConfirmed($authority->id);

        return [
            'id' => $authority->id,
            'stir' => $authority->stir,
            'name' => $authority->{LanguageHelper::getName()},
            'indicator_type_id' => $request ? $request->indicator_type_id : null,
            'request_id' => $request ? $request->id : null,
        ];
    }
}
