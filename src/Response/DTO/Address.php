<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Address
{
    private ?string $addressId = null;
    private string $name;
    private string $zipCode;
    private string $place;
    private string $street;
    private ?int $houseNumber = null;
    private string $houseNumberAddition = '';
    private string $countryPrefix;
    private ?string $phone = null;
    private ?string $fax = null;
    private ?string $textPhone = null;
    private ?string $email = null;
    private ?float $lat = null;
    private ?float $long = null;

    public function getAddressId(): ?string
    {
        return $this->addressId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouseNumber(): ?int
    {
        return $this->houseNumber;
    }

    public function getHouseNumberAddition(): string
    {
        return $this->houseNumberAddition;
    }

    public function getCountryPrefix(): string
    {
        return $this->countryPrefix;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function getTextPhone(): ?string
    {
        return $this->textPhone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }
}
