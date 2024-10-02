<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

enum DayType: int
{
    case WorkingDay = 1;
    case Monday = 7;
    case Tuesday = 8;
    case Wednesday = 9;
    case Thursday = 10;
    case Friday = 11;
    case Saturday = 2;
    case Saturday2 = 12;
    case Sunday = 3;

    public function getOrdinal(): int
    {
        return match ($this) {
            self::WorkingDay => 1,
            self::Monday => 2,
            self::Tuesday => 3,
            self::Wednesday => 4,
            self::Thursday => 5,
            self::Friday => 6,
            self::Saturday => 7,
            self::Saturday2 => 8,
            self::Sunday => 9,
        };
    }
}
