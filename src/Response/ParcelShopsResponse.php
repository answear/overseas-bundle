<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Response\DTO\Validation;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ParcelShopsResponse implements ResponseInterface, DenormalizableInterface
{
    /**
     * @var ParcelShop[]
     */
    private array $data = [];
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

    public function denormalize(
        DenormalizerInterface $denormalizer,
        $data,
        $format = null,
        array $context = []
    ) {
        $this->data = empty($data['data'])
            ? []
            : $denormalizer->denormalize(
                $data['data'],
                ParcelShop::class . '[]',
                $format,
                $context
            );
        $this->status = StatusResult::get($data['status']);
        $this->error = empty($data['error'])
            ? null : $denormalizer->denormalize(
                $data['error'],
                Error::class,
                $format,
                $context
            );
        $this->validations = empty($data['validations'])
            ? []
            : $denormalizer->denormalize(
                $data['validations'],
                Validation::class . '[]',
                $format,
                $context
            );
    }
}