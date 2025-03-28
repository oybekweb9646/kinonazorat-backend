<?php

namespace App\Core\Enums\Routes;

enum RoutePrefixEnum: string
{
    case _API = 'api';
    case _WEB = 'web';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
