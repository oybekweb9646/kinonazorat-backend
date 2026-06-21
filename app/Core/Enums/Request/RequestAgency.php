<?php

namespace App\Core\Enums\Request;

enum RequestAgency: int
{
    case AGENCY_NUMBER = 200936110; // O'zbekiston respublikasi kinematografiya agentligi   200936110

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
