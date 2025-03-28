<?php

namespace App\Core\Repository\Authority;

use App\Models\Authority;

class AuthorityRepository
{
    /**
     * @param Authority $authority
     * @param array $params
     * @return void
     */
    public function save(Authority $authority, array $params = []): void
    {
        if (!$authority->save($params)) {
            throw new \RuntimeException(__('client.authority save error'));
        }
    }

    public function getById(int $id)
    {
        return Authority::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getByStir(int $stir)
    {
        return Authority::query()
            ->where('stir', $stir)
            ->firstOrFail();
    }

    public function findByStir(int $stir)
    {
        return Authority::query()
            ->where('stir', $stir)
            ->first();
    }

    public function countChecked(): int
    {
        return Authority::query()
            ->where('is_checked_checklist', true)
            ->count();
    }

    public function countQuestion(): int
    {
        return Authority::query()
            ->where('is_checked_question', true)
            ->count();
    }
}
