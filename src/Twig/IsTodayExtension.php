<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IsTodayExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_today', [$this, 'isToday']),
        ];
    }

    public function isToday($value, $type = 'DAY_FULL_NAME')
    {
        $currDate = new \DateTime();
        switch ($type) {
            case 'DATE' :
                return date('d.m.Y H:i', strtotime($currDate->getTimestamp())) === $value;
            case 'DAY_FULL_NAME' :
                return $currDate->format('l') === $value;
            case 'DAY_SHORT_NAME' :
                return $currDate->format('D') === $value;
            case 'DAY_WEEK_INDEX' :
                $dayNames = array(
                    'Sunday' => 0,
                    'Monday' => 1,
                    'Tuesday' => 2,
                    'Wednesday' => 3,
                    'Thursday' => 4,
                    'Friday' => 5,
                    'Saturday' => 6,
                );
                return $dayNames[$currDate->format('l')] === $value;
            default :
                return false;
        };
    }
}
