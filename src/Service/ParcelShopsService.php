<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Service;

use Answear\OverseasBundle\Client\Client;
use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Exception\BadRequestException;
use Answear\OverseasBundle\Exception\ServiceUnavailableException;
use Answear\OverseasBundle\Request\GetParcelShops;
use Answear\OverseasBundle\Response\ParcelShopsResult;
use Answear\OverseasBundle\Serializer\Serializer;

class ParcelShopsService
{
    private Client $client;
    private Serializer $serializer;

    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     */
    public function get(): ParcelShopsResult
    {
        $response = $this->client->request(new GetParcelShops());

        /** @var ParcelShopsResult $parcelShopsResult */
        $parcelShopsResult = $this->serializer->decodeResponse(ParcelShopsResult::class, $response);

        if (!$parcelShopsResult->getStatus()->is(StatusResult::ok())) {
            throw new BadRequestException('Invalid request.', $parcelShopsResult);
        }

        return $parcelShopsResult;
    }
}
