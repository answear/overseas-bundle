<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Client;

use Answear\OverseasBundle\Exception\ServiceUnavailableException;
use Answear\OverseasBundle\Logger\OverseasLogger;
use Answear\OverseasBundle\Request\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const CONNECTION_TIMEOUT = 10;
    private const TIMEOUT = 30;

    private ClientInterface $client;
    private RequestTransformer $transformer;
    private OverseasLogger $overseasLogger;

    public function __construct(
        RequestTransformer $transformer,
        OverseasLogger $overseasLogger,
        ?ClientInterface $client = null
    ) {
        $this->transformer = $transformer;
        $this->overseasLogger = $overseasLogger;
        $this->client = $client ?? new \GuzzleHttp\Client(['timeout' => self::TIMEOUT, 'connect_timeout' => self::CONNECTION_TIMEOUT]);
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $this->overseasLogger->setRequestId(uniqid('OVERSEAS', true));
        try {
            $psrRequest = $this->transformer->transform($request);
            $this->overseasLogger->logRequest($request->getEndpoint(), $psrRequest);

            $psrResponse = $this->client->send($psrRequest);
            $this->overseasLogger->logResponse($request->getEndpoint(), $psrRequest, $psrResponse);

            if ($psrResponse->getBody()->isSeekable()) {
                $psrResponse->getBody()->rewind();
            }
        } catch (GuzzleException $e) {
            $this->overseasLogger->logError($request->getEndpoint(), $e);

            throw new ServiceUnavailableException($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $t) {
            $this->overseasLogger->logError($request->getEndpoint(), $t);

            throw $t;
        } finally {
            $this->overseasLogger->clearRequestId();
        }

        return $psrResponse;
    }
}
