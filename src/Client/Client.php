<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Client;

use Answear\OverseasBundle\Exception\ServiceUnavailableException;
use Answear\OverseasBundle\Request\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private ClientInterface $client;
    private RequestTransformer $transformer;

    public function __construct(RequestTransformer $transformer, ?ClientInterface $client = null)
    {
        $this->transformer = $transformer;
        $this->client = $client ?? new \GuzzleHttp\Client();
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->client->send($this->transformer->transform($request));

            if ($response->getBody()->isSeekable()) {
                $response->getBody()->rewind();
            }
        } catch (GuzzleException $e) {
            throw new ServiceUnavailableException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }
}
