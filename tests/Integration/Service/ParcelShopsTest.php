<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Tests\Integration\Service;

use Answear\OverseasBundle\Client\Client;
use Answear\OverseasBundle\Client\RequestTransformer;
use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\Enum\EnvironmentEnum;
use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Exception\BadRequestException;
use Answear\OverseasBundle\Logger\OverseasLogger;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\ParcelShop;
use Answear\OverseasBundle\Serializer\Serializer;
use Answear\OverseasBundle\Service\ParcelShopsService;
use Answear\OverseasBundle\Tests\MockGuzzleTrait;
use Answear\OverseasBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ParcelShopsTest extends TestCase
{
    use MockGuzzleTrait;

    private Serializer $serializer;
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer();
    }

    /**
     * @test
     */
    public function successfulGetOffices(): void
    {
        $this->client = $this->getClient();
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/parcelShops.json'))
        );

        $this->assertOfficeSame($service->get());
    }

    /**
     * @test
     */
    public function badRequestTest(): void
    {
        $this->client = $this->getClient(false);
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/parcelShops-invalid.json'))
        );

        try {
            $service->get();
        } catch (BadRequestException $exception) {
            self::assertSame('Error occurs.', $exception->getMessage());
            self::assertTrue($exception->getResponse()->getStatus()->is(StatusResult::error()));
            self::assertInstanceOf(Error::class, $exception->getResponse()->getError());

            return;
        }

        $this->fail('BadRequestException expected.');
    }

    private function getClient(?bool $withLogger = true): Client
    {
        return new Client(
            new RequestTransformer(
                $this->serializer,
                new ConfigProvider(EnvironmentEnum::TEST, 'api-key')
            ),
            new OverseasLogger($withLogger ? $this->getLogger() : new NullLogger()),
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
            $address = $parcelShop->address;
            $workingHours = $parcelShop->workingHours;

            $actualWorkingHours = [];
            foreach ($workingHours as $workingHour) {
                $actualWorkingHours[] = [
                    'type' => $workingHour->type->getValue(),
                    'typeName' => $workingHour->typeName,
                    'from' => $workingHour->from,
                    'until' => $workingHour->until,
                ];
            }

            $actualData[] = [
                'centerId' => $parcelShop->centerId,
                'countryId' => $parcelShop->countryId,
                'delivery' => $parcelShop->delivery,
                'dropoff' => $parcelShop->dropoff,
                'coordinates' => [
                    'latitude' => $parcelShop->getCoordinates()->latitude,
                    'longitude' => $parcelShop->getCoordinates()->longitude,
                ],
                'remark' => $parcelShop->remark,
                'shortName' => $parcelShop->shortName,
                'partner' => $parcelShop->partner,
                'address' => [
                    'name' => $address->name,
                    'addressId' => $address->addressId,
                    'countryPrefix' => $address->countryPrefix,
                    'email' => $address->email,
                    'phone' => $address->phone,
                    'textPhone' => $address->textPhone,
                    'fax' => $address->fax,
                    'houseNumber' => $address->houseNumber,
                    'houseNumberAddition' => $address->houseNumberAddition,
                    'coordinates' => null,
                    'place' => $address->place,
                    'street' => $address->street,
                    'zipcode' => $address->zipCode,
                ],
                'workingHours' => $actualWorkingHours,
                'isActive' => $parcelShop->isActive,
            ];
        }

        self::assertSame(
            FileTestUtil::decodeJsonFromFile(__DIR__ . '/data/parcelShops_expected_parcels.json'),
            $actualData
        );
    }

    private function getLogger(): LoggerInterface
    {
        $expected = [
            '[OVERSEAS] Request - parcelshops' => [
                'endpoint' => 'parcelshops',
                'uri' => [
                    'path' => '/parcelshops',
                    'query' => [
                        'apiKey' => 'api-key',
                    ],
                ],
                'body' => null,
            ],
            '[OVERSEAS] Response - parcelshops' => [
                'endpoint' => 'parcelshops',
                'uri' => [
                    'path' => '/parcelshops',
                    'query' => [
                        'apiKey' => 'api-key',
                    ],
                ],
                'response' => '--- HUGE CONTENT SKIPPED ---',
            ],
        ];
        $actualMessage = null;

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::exactly(2))
            ->method('info')
            ->with(
                $this->callback(
                    static function (string $message) use ($expected, &$actualMessage) {
                        self::assertIsArray($expected[$message] ?? null);
                        $actualMessage = $message;

                        return true;
                    }
                ),
                $this->callback(
                    static function (array $context = []) use ($expected, &$actualMessage) {
                        self::assertIsString($context['overseasRequestId']);
                        unset($context['overseasRequestId']);

                        self::assertEquals($expected[$actualMessage] ?? [], $context);
                        self::assertSame($expected[$actualMessage] ?? [], $context);

                        return true;
                    }
                )
            );

        return $logger;
    }
}
