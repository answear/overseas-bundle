<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class ParcelShop
{
    public int $countryId;
    public int $centerId;
    public string $shortName;
    public string $remark = '';
    public ?bool $delivery;
    public ?bool $dropoff;
    public Address $address;
    /**
     * @var WorkingHours[]
     */
    public array $workingHours = [];
    public bool $isActive = true;
    private ?float $geoLat;
    private ?float $geoLong;

    public function addWorkingHours(WorkingHours $workingHours): void
    {
        $this->workingHours[] = $workingHours;
    }

    public function getCoordinates(): ?Coordinates
    {
        return null !== $this->geoLat && null !== $this->geoLong
            ? new Coordinates($this->geoLat, $this->geoLong)
            : null;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }
}
