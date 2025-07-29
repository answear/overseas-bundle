<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Place
{
    public function __construct(
        public string $zipCode,
        public string $name,
        public string $zipcodeName,
        public ?bool $standardAvailable = null,
        public ?bool $cargoAvailable = null,
    ) {
    }

    public static function fromArray(array $data): Place
    {
        return new self(
            $data['ZipCode'],
            $data['Name'],
            $data['ZipcodeName'],
            $data['StandardAvailable'] ?? null,
            $data['CargoAvailable'] ?? null,
        );
    }
}
