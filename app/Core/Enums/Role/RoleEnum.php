<?php

namespace App\Core\Enums\Role;

enum RoleEnum: int
{
    case _RESPONSIBLE = 1;
    case _SUPER_ADMIN = 2;
    case _READ_ONLY = 3;

    case _AUTHORITY = 4;
    case _TERRITORIAL_RESPONSIBLE = 5;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
