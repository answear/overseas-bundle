<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Error
{
    /**
     * @var Validation[]
     */
    private array $validations;
    private int $code;

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
