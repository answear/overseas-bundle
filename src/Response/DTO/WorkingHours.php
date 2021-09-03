<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

use Answear\OverseasBundle\Enum\DayType;

class WorkingHours
{
    private ?DayType $type;
    private string $typeName;
    private ?string $from;
    private ?string $until;

    public function getType(): ?DayType
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getUntil(): ?string
    {
        return $this->until;
    }
}
