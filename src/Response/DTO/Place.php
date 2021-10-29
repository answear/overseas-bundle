<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Place
{
    public string $zipCode;
    public string $name;
    public string $zipcodeName;
    public ?bool $standardAvailable;
    public ?bool $cargoAvailable;

    public function __construct(string $zipCode, string $name, string $zipcodeName, ?bool $standardAvailable = null, ?bool $cargoAvailable = null)
    {
        $this->zipCode = $zipCode;
        $this->name = $name;
        $this->zipcodeName = $zipcodeName;
        $this->standardAvailable = $standardAvailable;
        $this->cargoAvailable = $cargoAvailable;
    }
}
