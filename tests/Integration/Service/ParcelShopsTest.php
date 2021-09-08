<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Tests\Integration\Service;

use Answear\OverseasBundle\Client\Client;
use Answear\OverseasBundle\Client\RequestTransformer;
use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\Enum\EnvironmentEnum;
use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Serializer\Serializer;
use Answear\OverseasBundle\Service\ParcelShopsService;
use Answear\OverseasBundle\Tests\MockGuzzleTrait;
use Answear\OverseasBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ParcelShopsTest extends TestCase
{
    use MockGuzzleTrait;

    private Serializer $serializer;
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer();
        $this->client = $this->getClient();
    }

    /**
     * @test
     */
    public function successfulGetOffices(): void
    {
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/parcelShops.json'))
        );

        $response = $service->get();

        self::assertTrue(StatusResult::ok()->is($response->getStatus()));
        self::assertEmpty($response->getError());
        self::assertSame([], $response->getValidations());
        $this->assertOfficeSame($response->getData());
    }

    /**
     * @test
     */
    public function getOfficesWithError(): void
    {
        //TODO handle response with error
        $this->markTestIncomplete('TODO handle response with error.');
        /*
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/parcelShops-invalid.json'))
        );

        $response = $service->get();
        */
    }

    private function getClient(): Client
    {
        return new Client(
            new RequestTransformer(
                $this->serializer,
                new ConfigProvider(EnvironmentEnum::test(), '')
            ),
            $this->setupGuzzleClient()
        );
    }

    private function getService(): ParcelShopsService
    {
        return new ParcelShopsService($this->client, $this->serializer);
    }

    /**
     * @param ParcelShop[] $parcelShops
     */
    private function assertOfficeSame(array $parcelShops): void
    {
        $actualData = [];
        foreach ($parcelShops as $parcelShop) {
            $address = $parcelShop->getAddress();
            $workingHours = $parcelShop->getWorkingHours();

            $actualWorkingHours = [];
            foreach ($workingHours as $workingHour) {
                $actualWorkingHours[] = [
                    'type' => $workingHour->getType()->getValue(),
                    'typeName' => $workingHour->getTypeName(),
                    'from' => $workingHour->getFrom(),
                    'until' => $workingHour->getUntil(),
                ];
            }

            $actualData[] = [
                'centerId' => $parcelShop->getCenterId(),
                'countryId' => $parcelShop->getCountryId(),
                'delivery' => $parcelShop->getDelivery(),
                'dropoff' => $parcelShop->getDropoff(),
                'geoLat' => $parcelShop->getGeoLat(),
                'geoLong' => $parcelShop->getGeoLong(),
                'remark' => $parcelShop->getRemark(),
                'shortName' => $parcelShop->getShortName(),
                'address' => [
                    'name' => $address->getName(),
                    'addressId' => $address->getAddressId(),
                    'countryPrefix' => $address->getCountryPrefix(),
                    'email' => $address->getEmail(),
                    'phone' => $address->getPhone(),
                    'textPhone' => $address->getTextPhone(),
                    'fax' => $address->getFax(),
                    'houseNumber' => $address->getHouseNumber(),
                    'houseNumberAddition' => $address->getHouseNumberAddition(),
                    'lat' => $address->getLat(),
                    'long' => $address->getLong(),
                    'place' => $address->getPlace(),
                    'street' => $address->getStreet(),
                    'zipcode' => $address->getZipCode(),
                ],
                'workingHours' => $actualWorkingHours,
                'isActive' => $parcelShop->isActive(),
            ];
        }

        self::assertSame(
            FileTestUtil::decodeJsonFromFile(__DIR__ . '/data/parcelShops_expected_parcels.json'),
            $actualData
        );
    }
}
