<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

class Validation
{
    private int $code;
    private string $message;

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
