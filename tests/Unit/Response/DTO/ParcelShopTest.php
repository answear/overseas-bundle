<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Tests\Unit\Response\DTO;

use Answear\OverseasBundle\Enum\DayType;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Response\DTO\WorkingHours;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParcelShopTest extends TestCase
{
    #[Test]
    #[DataProvider('provideWorkingHoursSortedCorrectly')]
    public function workingHoursSortedCorrectly(array $workingHours, array $expected): void
    {
        $shop = new ParcelShop();
        $shop->workingHours = $workingHours;
        self::assertSame($expected, $shop->getSortedWorkingHours());
    }

    public static function provideWorkingHoursSortedCorrectly(): iterable
    {
        $nulledType = new WorkingHours();
        $workingDays = self::createWorkingHours(DayType::WorkingDay);
        $monday = self::createWorkingHours(DayType::Monday);
        $tuesday = self::createWorkingHours(DayType::Tuesday);
        $wednesday = self::createWorkingHours(DayType::Wednesday);
        $thursday = self::createWorkingHours(DayType::Thursday);
        $friday = self::createWorkingHours(DayType::Friday);
        $saturday = self::createWorkingHours(DayType::Saturday);
        $sunday = self::createWorkingHours(DayType::Sunday);

        yield 'with working days, already sorted' => [
            [$workingDays, $saturday, $sunday],
            [$workingDays, $saturday, $sunday],
        ];
        yield 'with working days, scrambled' => [
            [$sunday, $workingDays, $saturday],
            [$workingDays, $saturday, $sunday],
        ];
        yield 'full week, sorted' => [
            [$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday],
            [$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday],
        ];
        yield 'full week, scrambled' => [
            [$saturday, $tuesday, $monday, $wednesday, $friday, $thursday, $sunday],
            [$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday],
        ];
        yield 'nulled type is first' => [
            [$workingDays, $saturday, $sunday, $nulledType],
            [$nulledType, $workingDays, $saturday, $sunday],
        ];

        $saturdayFirstPart = self::createWorkingHours(DayType::Saturday, '09:00', '11:00');
        $saturdaySecondPart = self::createWorkingHours(DayType::Saturday, '14:00', '16:00');
        yield 'day parts' => [
            [$saturdaySecondPart, $workingDays, $saturdayFirstPart, $sunday],
            [$workingDays, $saturdayFirstPart, $saturdaySecondPart, $sunday],
        ];
    }

    private static function createWorkingHours(DayType $dayType, string $from = '09:00', string $until = '17:00'): WorkingHours
    {
        $workingHours = new WorkingHours();
        $workingHours->setType($dayType);
        $workingHours->from = $from;
        $workingHours->until = $until;

        return $workingHours;
    }
}
