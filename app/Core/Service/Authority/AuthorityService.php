<?php

namespace App\Core\Service\Authority;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\User\UserRepository;
use App\Http\Requests\Authority\AuthorityRequest;
use App\Models\Authority;
use App\Models\User;

class AuthorityService
{
    public function __construct(
        private AuthorityRepository $authorityRepository,
        private UserRepository      $userRepository,
        private Transaction         $transaction
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
}
