<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class ParcelShop
{
    private int $countryId;
    private int $centerId;
    private string $shortName;
    private string $remark = '';
    private ?bool $delivery;
    private ?bool $dropoff;
    private ?float $geoLat;
    private ?float $geoLong;
    private Address $address;
    /**
     * @var WorkingHours[]
     */
    private array $workingHours = [];
    private bool $isActive = true;

    public function getCountryId(): int
    {
        return $this->countryId;
    }

    public function getCenterId(): int
    {
        return $this->centerId;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getRemark(): string
    {
        return $this->remark;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function getDropoff(): ?bool
    {
        return $this->dropoff;
    }

    public function getGeoLat(): ?float
    {
        return $this->geoLat;
    }

    public function getGeoLong(): ?float
    {
        return $this->geoLong;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return WorkingHours[]
     */
    public function getWorkingHours(): array
    {
        return $this->workingHours;
    }

    public function addWorkingHours(WorkingHours $workingHours): void
    {
        $this->workingHours[] = $workingHours;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
