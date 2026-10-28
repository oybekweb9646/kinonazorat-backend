<?php

namespace App\Core\Enums\Request;

enum RiskLevel: string
{
    case LOW = 'low';       // past xavf (0-60)
    case MEDIUM = 'medium'; // o'rta xavf (61-80)
    case HIGH = 'high';     // yuqori xavf (81-100)

    /**
     * Ball bo'yicha xavf darajasini aniqlaydi.
     * Chegaralar RequestController::stat() mantig'iga mos: high 81-100, normal 61-80, low 0-60.
     *
     * @param int|null $score
     * @return self|null
     */
    public static function fromScore(?int $score): ?self
    {
        if ($score === null) {
            return null;
        }

        return match (true) {
            $score >= 81 => self::HIGH,
            $score >= 61 => self::MEDIUM,
            default => self::LOW,
        };
    }
}
