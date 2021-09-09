<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

use Answear\OverseasBundle\Enum\DayType;

class WorkingHours
{
    public ?DayType $type;
    public string $typeName;
    public ?string $from;
    public ?string $until;

    public function setType(?DayType $type): void
    {
        $this->type = $type;
    }
}
