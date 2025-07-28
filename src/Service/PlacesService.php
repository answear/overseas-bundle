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
use Psr\Http\Message\ResponseInterface;

class PlacesService
{
    public function __construct(
        private Client $client,
        private Serializer $serializer,
    ) {
    }

    /**
     * @return Place[]
     *
     * @throws ServiceUnavailableException
     * @throws BadRequestException
     */
    public function get(?string $zipCode = null, ?string $name = null, ?bool $approx = null): array
    {
        $response = $this->getResponse($zipCode, $name, $approx);

        /** @var PlacesResponse $placesResponse */
        $placesResponse = $this->serializer->decodeResponse(PlacesResponse::class, $response);

        if (StatusResult::Ok !== $placesResponse->getStatus()) {
            throw new BadRequestException($placesResponse);
        }

        return $placesResponse->data;
    }

    public function getResponse(?string $zipCode = null, ?string $name = null, ?bool $approx = null): ResponseInterface
    {
        return $this->client->request(new GetPlaces($zipCode, $name, $approx));
    }
}
