<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Error implements DenormalizableInterface
{
    /**
     * @var Validation[]
     */
    private array $validations = [];
    private int $code;

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = null, array $context = [])
    {
        $this->validations = empty($data['Validations'])
            ? []
            : $denormalizer->denormalize(
                $data['Validations'],
                Validation::class . '[]',
                $format,
                $context
            );

        $this->code = $data['Code'];
    }
}
