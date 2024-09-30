<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Request;

class GetPlaces implements RequestInterface
{
    private const ENDPOINT = 'places';
    private const HTTP_METHOD = 'GET';

    public function __construct(
        public readonly ?string $zipCode = null,
        public readonly ?string $name = null,
        public readonly ?bool $approx = null,
    ) {
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrlQuery(): ?string
    {
        $urlQuery = http_build_query(
            [
                'zipcode' => $this->zipCode,
                'name' => $this->name,
                'approx' => null === $this->approx ? null : (true === $this->approx ? 'true' : 'false'),
            ]
        );

        return empty($urlQuery) ? null : $urlQuery;
    }
}
