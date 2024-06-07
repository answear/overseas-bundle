<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\Place;
use Answear\OverseasBundle\Response\DTO\Validation;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PlacesResponse implements ResponseInterface, DenormalizableInterface
{
    use ResponsePartialDenormalizeTrait;

    /**
     * @var Place[]
     */
    public array $data = [];
    public StatusResult $status;
    public ?Error $error = null;
    /**
     * @var Validation[]
     */
    public array $validations = [];

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
    ): void {
        $this->data = empty($data['data'])
            ? []
            : $denormalizer->denormalize(
                $data['data'],
                Place::class . '[]',
                $format,
                $context
            );
        $this->status = $this->denormalizeStatus($data);
        $this->error = $this->denormalizeError($denormalizer, $data, $format, $context);
        $this->validations = $this->denormalizeValidation($denormalizer, $data, $format, $context);
    }
}
