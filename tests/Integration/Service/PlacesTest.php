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
use Answear\OverseasBundle\Response\DTO\Place;
use Answear\OverseasBundle\Serializer\Serializer;
use Answear\OverseasBundle\Service\PlacesService;
use Answear\OverseasBundle\Tests\MockGuzzleTrait;
use Answear\OverseasBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PlacesTest extends TestCase
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
     * @dataProvider providePlacesParams
     */
    public function successfulPlaces(?string $zipCode = null, ?string $name = null, ?bool $approx = null): void
    {
        $this->client = $this->getClient(
            true,
            [
                'zipcode' => $zipCode,
                'name' => $name,
                'approx' => null === $approx ? null : (false === $approx ? 'false' : 'true'),
            ]
        );
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/places.json'))
        );

        $this->assertPlacesSame($service->get($zipCode, $name, $approx));
    }

    public function providePlacesParams(): iterable
    {
        yield [];

        yield [null, 'name', null];

        yield ['23764', 'name', false];

        yield ['23764', 'name', true];
    }

    /**
     * @test
     */
    public function badRequestTest(): void
    {
        $this->client = $this->getClient(false);
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/places-invalid.json'))
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

    /**
     * @test
     */
    public function invalidZipcodeTest(): void
    {
        $this->client = $this->getClient(false);
        $service = $this->getService();
        $this->mockGuzzleResponse(
            new Response(200, [], FileTestUtil::getFileContents(__DIR__ . '/data/places-invalid-zipcode.json'))
        );

        $places = $service->get();
        self::assertSame([], $places);
    }

    private function getClient(?bool $withLogger = true, array $uriParams = []): Client
    {
        return new Client(
            new RequestTransformer(
                $this->serializer,
                new ConfigProvider(EnvironmentEnum::TEST, 'api-key')
            ),
            new OverseasLogger($withLogger ? $this->getLogger($uriParams) : new NullLogger()),
            $this->setupGuzzleClient()
        );
    }

    private function getService(): PlacesService
    {
        return new PlacesService($this->client, $this->serializer);
    }

    /**
     * @param Place[] $places
     */
    private function assertPlacesSame(array $places): void
    {
        $actualData = [];
        foreach ($places as $place) {
            $actualData[] = [
                'zipCode' => $place->zipCode,
                'name' => $place->name,
                'zipCodeName' => $place->zipcodeName,
                'standardAvailable' => $place->standardAvailable,
                'cargoAvailable' => $place->cargoAvailable,
            ];
        }

        self::assertSame(
            FileTestUtil::decodeJsonFromFile(__DIR__ . '/data/places_expected.json'),
            $actualData
        );
    }

    private function getLogger(array $uriParams = []): LoggerInterface
    {
        foreach ($uriParams as $key => $uriParam) {
            if (null === $uriParam) {
                unset($uriParams[$key]);
            }
        }
        $query = array_merge(['apiKey' => 'api-key'], $uriParams);

        $expected = [
            '[OVERSEAS] Request - places' => [
                'endpoint' => 'places',
                'uri' => [
                    'path' => '/parcelshops',
                    'query' => $query,
                ],
                'body' => null,
            ],
            '[OVERSEAS] Response - places' => [
                'endpoint' => 'places',
                'uri' => [
                    'path' => '/places',
                    'query' => $query,
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
