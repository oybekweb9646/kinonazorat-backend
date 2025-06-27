<?php

namespace App\Core\Enums\Action;

enum Action: string
{
    case REQUEST_CREATED = 'REQUEST_CREATED'; // So'rov yaratildi
    case REQUEST_DELETED = 'REQUEST_DELETED'; // So'rov o'chirildi
    case REQUEST_SCORE_CHANGED = 'REQUEST_SCORE_CHANGED'; // Baholangan so'rov o'zgartirildi
    case REQUEST_SCORED = 'REQUEST_SCORED'; // So'rov baholandi
    case REQUEST_SEND_FOR_INSECTION = 'REQUEST_SEND_FOR_INSECTION'; // So'rov tekshiruv uchun yuborildi
    case REQUEST_ARCHIVED = 'REQUEST_ARCHIVED'; // So'rov arxivga olindi
    case REQUEST_SET_FILE = 'REQUEST_SET_FILE'; // So'rov uchun fayl qo'shildi

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
