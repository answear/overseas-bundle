<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Client;

use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\Request\RequestInterface;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Psr7\Uri;

class RequestTransformer
{
    private Serializer $serializer;
    private ConfigProvider $configuration;

    public function __construct(
        Serializer $serializer,
        ConfigProvider $configuration
    ) {
        $this->serializer = $serializer;
        $this->configuration = $configuration;
    }

    public function transform(RequestInterface $request): HttpRequest
    {
        return new HttpRequest(
            $request->getMethod(),
            new Uri($this->configuration->getUrl() . $request->getEndpoint()),
            [
                'Content-type' => 'application/json',
            ],
            $this->serializer->serialize($request)
        );
    }
}
