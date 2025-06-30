<?php

namespace App\Core\Enums\Request;

enum RequestAngecy: int
{
    case AGENCY_NUMBER = 200898285; // Axborot va ommaviy kommunikatsiyalar agentligi   200898285

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
