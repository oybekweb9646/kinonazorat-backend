<?php

namespace App\Core\Enums\Action;

enum Action: string
{
    case REQUEST_CREATED = 'REQUEST_CREATED';
    case REQUEST_DELETED = 'REQUEST_DELETED';
    case REQUEST_SCORED = 'REQUEST_SCORED';
    case REQUEST_CONFIRMED = 'REQUEST_CONFIRMED';
    case REQUEST_SCORE_CHANGED = 'REQUEST_SCORE_CHANGED';
    case REQUEST_SET_FILE = 'REQUEST_SET_FILE';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
