<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Tests\Unit\Response\DTO;

use Answear\OverseasBundle\Enum\DayType;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Response\DTO\WorkingHours;
use PHPUnit\Framework\TestCase;

class ParcelShopTest extends TestCase
{
    /**
     * @dataProvider provideWorkingHoursSortedCorrectly
     * @test
     */
    public function workingHoursSortedCorrectly(array $workingHours, array $expected): void
    {
        $shop = new ParcelShop();
        $shop->workingHours = $workingHours;
        self::assertSame($expected, $shop->getSortedWorkingHours());
    }

    public function provideWorkingHoursSortedCorrectly(): iterable
    {
        $nulledType = new WorkingHours();
        $workingDays = $this->createWorkingHours(DayType::workingDay());
        $monday = $this->createWorkingHours(DayType::monday());
        $tuesday = $this->createWorkingHours(DayType::tuesday());
        $wednesday = $this->createWorkingHours(DayType::wednesday());
        $thursday = $this->createWorkingHours(DayType::thursday());
        $friday = $this->createWorkingHours(DayType::friday());
        $saturday = $this->createWorkingHours(DayType::saturday());
        $sunday = $this->createWorkingHours(DayType::sunday());

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

        $saturdayFirstPart = $this->createWorkingHours(DayType::saturday(), '09:00', '11:00');
        $saturdaySecondPart = $this->createWorkingHours(DayType::saturday(), '14:00', '16:00');
        yield 'day parts' => [
            [$saturdaySecondPart, $workingDays, $saturdayFirstPart, $sunday],
            [$workingDays, $saturdayFirstPart, $saturdaySecondPart, $sunday],
        ];
    }

    private function createWorkingHours(DayType $dayType, string $from = '09:00', string $until = '17:00'): WorkingHours
    {
        $workingHours = new WorkingHours();
        $workingHours->setType($dayType);
        $workingHours->from = $from;
        $workingHours->until = $until;

        return $workingHours;
    }
}
