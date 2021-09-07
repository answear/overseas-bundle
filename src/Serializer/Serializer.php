<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Serializer;

use Answear\OverseasBundle\Request\RequestInterface;
use Answear\OverseasBundle\Serializer\Normalizer\EnumNormalizer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer
{
    private const FORMAT = 'json';

    private SymfonySerializer $serializer;

    public function serialize(RequestInterface $request): string
    {
        return $this->getSerializer()->serialize(
            $request,
            static::FORMAT,
            [Normalizer\AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );
    }

    public function decodeResponse(string $class, ResponseInterface $response): object
    {
        return $this->getSerializer()->deserialize(
            $response->getBody()->getContents(),
            $class,
            static::FORMAT
        );
    }

    private function getSerializer(): SymfonySerializer
    {
        if (!isset($this->serializer)) {
            $this->serializer = new SymfonySerializer(
                [
                    new Normalizer\CustomNormalizer(),
                    new Normalizer\DateTimeNormalizer(
                        [
                            Normalizer\DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\\TH:i:s.uP',
                        ]
                    ),
                    new EnumNormalizer(),
                    new Normalizer\PropertyNormalizer(
                        null,
                        new CamelCaseToPascalCaseNameConverter(),
                        new ReflectionExtractor()
                    ),
                    new Normalizer\ArrayDenormalizer(),
                ],
                [new JsonEncoder()]
            );
        }

        return $this->serializer;
    }
}
