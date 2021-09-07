<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Request;

class GetParcelShops implements RequestInterface
{
    private const ENDPOINT = 'parcelshops';
    private const HTTP_METHOD = 'POST';

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMethod(): string
    {
        return self::HTTP_METHOD;
    }
}
