<?php

namespace App\Core\Repository\checklist;

use App\Models\ChecklistAuthority;

class ChecklistAuthorityRepository
{
    public function getByIdObject(int $id)
    {
        return ChecklistAuthority::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function findByAuthorityAndChecklist(int $authorityId, int $checklistId)
    {
        return ChecklistAuthority::query()
            ->where('authority_id', $authorityId)
            ->where('checklist_id', $checklistId)
            ->first();
    }
}
