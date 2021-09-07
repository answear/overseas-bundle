<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Address
{
    public ?string $addressId = null;
    public string $name;
    public string $zipCode;
    public string $place;
    public string $street;
    public ?int $houseNumber = null;
    public string $houseNumberAddition = '';
    public string $countryPrefix;
    public ?string $phone = null;
    public ?string $fax = null;
    public ?string $textPhone = null;
    public ?string $email = null;
    private ?float $lat = null;
    private ?float $long = null;

    public function getCoordinates(): ?Coordinates
    {
        return null !== $this->lat && null !== $this->long ? new Coordinates($this->lat, $this->long) : null;
    }
}
