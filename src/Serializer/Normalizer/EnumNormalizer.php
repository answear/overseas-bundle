<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!$object instanceof \BackedEnum) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', \BackedEnum::class));
        }

        return $object->value;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof \BackedEnum;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        try {
            $type::from($data);
        } catch (\Throwable $t) {
            return false;
        }

        return true;
    }

    /**
     * @see \BackedEnum for $type
     */
    public function denormalize($data, $type, $format = null, array $context = []): \BackedEnum
    {
        return $type::from($data);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            \BackedEnum::class => true,
        ];
    }
}
