<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Serializer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class CamelCaseToPascalCaseNameConverter implements NameConverterInterface
{
    public function normalize($propertyName)
    {
        return ucfirst($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($propertyName)
    {
        $doubleU = preg_replace_callback(
            '/[A-Z]{2,}/',
            static function ($match) {
                return ucfirst(mb_strtolower($match[0]));
            },
            $propertyName
        );

        return lcfirst($doubleU);
    }
}
