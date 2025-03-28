<?php

namespace App\Core\Enums\User;

enum UserStatusEnum: int
{
    case _ACTIVE = 1;
    case _DELETED = 0;
    case _BANNED = 2;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
