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
    public array $validations = [];
    public int $code;

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = null, array $context = []): void
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
