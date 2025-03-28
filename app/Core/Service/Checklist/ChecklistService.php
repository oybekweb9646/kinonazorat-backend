<?php

namespace App\Core\Service\Checklist;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\checklist\ChecklistAuthorityRepository;
use App\Core\Repository\checklist\ChecklistRepository;
use App\Http\Requests\Checklist\ChecklistRequest;
use App\Models\Checklist;
use App\Models\ChecklistAuthority;

class ChecklistService
{
    public function __construct(
        private Transaction                  $transaction,
        private ChecklistRepository          $checklistRepository,
        private ChecklistAuthorityRepository $checklistAuthorityRepository,
        private AuthorityRepository          $authorityRepository
    )
    {
    }

    public function create(ChecklistRequest $request): Checklist
    {
        $checklist = new Checklist();

        $checklist->fill($request->all());

        $this->transaction->wrap(function () use ($checklist) {
            $checklist->save();
        });

        return $checklist;
    }

    public function update(checklistRequest $request, int $id): checklist
    {

        $checklist = $this->checklistRepository->getByIdObject($id);
        $checklist->fill($request->all());

        $this->transaction->wrap(function () use ($checklist) {
            $checklist->save();
        });

        return $checklist;
    }

    public function delete(int $id): checklist
    {
        $checklist = $this->checklistRepository->getByIdObject($id);

        $this->transaction->wrap(function () use ($checklist) {
            $checklist->delete();
        });

        return $checklist;
    }

    public function check(Checklist $checklist, $request): ChecklistAuthority
    {
        $checklistAuthority = $this->checklistAuthorityRepository->findByAuthorityAndChecklist(auth()->user()->authority_id, $checklist->id);
        if (is_null($checklistAuthority)) {
            $checklistAuthority = new ChecklistAuthority();
        }
        $checklistAuthority->authority_id = auth()->user()->authority_id;
        $checklistAuthority->checklist_id = $checklist->id;
        $checklistAuthority->stir = auth()->user()->stir;
        $checklistAuthority->is_checked = $request['is_checked'];

        $this->transaction->wrap(function () use ($checklistAuthority) {
            $checklistAuthority->save();
        });

        return $checklistAuthority;
    }

    public function confirm(int $id)
    {
        $authority = $this->authorityRepository->getById(auth()->user()->authority_id);
        $authority->is_checked_checklist = true;
        $authority->indicator_type_id = $id;

        $this->transaction->wrap(function () use ($authority) {
            $authority->save();
        });

        return $authority;
    }
}
