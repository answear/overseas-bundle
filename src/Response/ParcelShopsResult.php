<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Response\DTO\Validation;

class ParcelShopsResult implements ResponseInterface
{
    /**
     * @var ParcelShop[]|null
     */
    private ?array $data = null;
    private StatusResult $status;
    private ?Error $error = null;
    /**
     * @var Validation[]
     */
    private array $validations = [];

    /**
     * @return ParcelShop[]|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    public function addData(ParcelShop $parcelShop): void
    {
        $this->data[] = $parcelShop;
    }

    public function getStatus(): StatusResult
    {
        return $this->status;
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    /**
     * @return Validation[]
     */
    public function getValidations(): array
    {
        return $this->validations;
    }
}
