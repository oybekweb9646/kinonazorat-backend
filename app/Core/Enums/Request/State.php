<?php

namespace App\Core\Enums\Request;

enum State: int
{
    case CREATED = 1; // yaratilgan
    case PARTLY_SCORED = 2; // qisman baholangan
    case SCORED = 3; // yakunlash tugmasi bosilgan
    case SEND_FOR_INSECTION = 4; // tekshiruvga yuborilgan
    case ARCHIVED = 10; // arxivga tushgan

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
