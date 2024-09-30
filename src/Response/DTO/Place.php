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
}
