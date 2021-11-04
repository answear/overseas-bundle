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

    /**
     * @return WorkingHours[]
     */
    public function getSortedWorkingHours(): array
    {
        $copy = $this->workingHours;
        usort(
            $copy,
            static function (WorkingHours $a, WorkingHours $b): int {
                if (null === $a->type && null === $b->type) {
                    return 0;
                }
                if (null === $a->type) {
                    return -1;
                }
                if (null === $b->type) {
                    return 1;
                }
                if ($a->type->is($b->type)) {
                    return $a->from <=> $b->from;
                }

                return $a->type->getOrdinal() <=> $b->type->getOrdinal();
            }
        );

        return $copy;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function setGeoLat(?float $geoLat): void
    {
        $this->geoLat = $geoLat;
    }

    public function setGeoLong(?float $geoLong): void
    {
        $this->geoLong = $geoLong;
    }
}
