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

    /**
     * Holatning inson o'qiydigan nomi (uz).
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Yaratilgan',
            self::PARTLY_SCORED => 'Qisman baholangan',
            self::SCORED => 'Yakunlangan',
            self::SEND_FOR_INSECTION => 'Tekshiruvga yuborilgan',
            self::ARCHIVED => 'Arxivlangan',
        };
    }
}
