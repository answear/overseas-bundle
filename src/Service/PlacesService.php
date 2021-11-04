<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Service;

use Answear\OverseasBundle\Client\Client;
use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Exception\BadRequestException;
use Answear\OverseasBundle\Exception\ServiceUnavailableException;
use Answear\OverseasBundle\Request\GetPlaces;
use Answear\OverseasBundle\Response\DTO\Place;
use Answear\OverseasBundle\Response\PlacesResponse;
use Answear\OverseasBundle\Serializer\Serializer;

class PlacesService
{
    private Client $client;
    private Serializer $serializer;

    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @return Place[]
     *
     * @throws ServiceUnavailableException
     * @throws BadRequestException
     */
    public function get(?string $zipCode = null, ?string $name = null, ?bool $approx = null): array
    {
        $response = $this->client->request(new GetPlaces($zipCode, $name, $approx));

        /** @var PlacesResponse $placesResponse */
        $placesResponse = $this->serializer->decodeResponse(PlacesResponse::class, $response);

        if (!$placesResponse->getStatus()->is(StatusResult::ok())) {
            throw new BadRequestException($placesResponse);
        }

        return $placesResponse->data;
    }
}
