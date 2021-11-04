<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\Validation;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

trait ResponsePartialDenormalizeTrait
{
    public function denormalizeStatus(array $data): StatusResult
    {
        return StatusResult::get($data['status']);
    }

    public function denormalizeError(
        DenormalizerInterface $denormalizer,
        $data,
        $format = null,
        array $context = []
    ): ?Error {
        return empty($data['error'])
            ? null : $denormalizer->denormalize(
                $data['error'],
                Error::class,
                $format,
                $context
            );
    }

    /**
     * @return Validation[]
     */
    public function denormalizeValidation(
        DenormalizerInterface $denormalizer,
        $data,
        $format = null,
        array $context = []
    ): array {
        return empty($data['validations'])
            ? []
            : $denormalizer->denormalize(
                $data['validations'],
                Validation::class . '[]',
                $format,
                $context
            );
    }
}
