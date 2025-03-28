<?php

namespace App\Core\Enums\Request;

enum State: int
{
    case CREATED = 1;
    case PARTLY_SCORED = 2;
    case SCORED = 3;
    case CONFIRMED = 4;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
