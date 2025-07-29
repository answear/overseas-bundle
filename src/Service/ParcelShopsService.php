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
use Psr\Http\Message\ResponseInterface;

class ParcelShopsService
{
    public function __construct(
        private Client $client,
        private Serializer $serializer,
    ) {
    }

    /**
     * @return ParcelShop[]
     *
     * @throws ServiceUnavailableException
     * @throws BadRequestException
     */
    public function get(): array
    {
        $response = $this->getResponse();

        /** @var ParcelShopsResponse $parcelShopsResult */
        $parcelShopsResult = $this->serializer->decodeResponse(ParcelShopsResponse::class, $response);

        if (StatusResult::Ok !== $parcelShopsResult->getStatus()) {
            throw new BadRequestException($parcelShopsResult);
        }

        return $parcelShopsResult->data;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->client->request(new GetParcelShops());
    }
}
