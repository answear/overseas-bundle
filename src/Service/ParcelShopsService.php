<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Service;

use Answear\OverseasBundle\Client\Client;
use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Exception\BadRequestException;
use Answear\OverseasBundle\Exception\ServiceUnavailableException;
use Answear\OverseasBundle\Request\GetParcelShops;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Response\ParcelShopsResponse;
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
     * @return ParcelShop[]
     *
     * @throws ServiceUnavailableException
     * @throws BadRequestException
     */
    public function get(): array
    {
        $response = $this->client->request(new GetParcelShops());

        /** @var ParcelShopsResponse $parcelShopsResult */
        $parcelShopsResult = $this->serializer->decodeResponse(ParcelShopsResponse::class, $response);

        if (!$parcelShopsResult->getStatus()->is(StatusResult::ok())) {
            throw new BadRequestException($parcelShopsResult);
        }

        return $parcelShopsResult->getData();
    }
}
