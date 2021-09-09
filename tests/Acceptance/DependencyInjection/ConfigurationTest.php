<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Tests\Acceptance\DependencyInjection;

use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\DependencyInjection\AnswearOverseasExtension;
use Answear\OverseasBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     * @dataProvider provideValidConfig
     */
    public function validTest(array $configs): void
    {
        $this->assertConfigurationIsValid($configs);

        $extension = $this->getExtension();

        $builder = new ContainerBuilder();
        $extension->load($configs, $builder);

        $configProviderDefinition = $builder->getDefinition(ConfigProvider::class);

        self::assertSame($configs[0]['environment'], $configProviderDefinition->getArgument(0));
        self::assertSame((string) $configs[0]['apiKey'], $configProviderDefinition->getArgument(1));
    }

    /**
     * @test
     * @dataProvider provideInvalidConfig
     */
    public function invalid(array $config, ?string $expectedMessage = null): void
    {
        $this->assertConfigurationIsInvalid(
            $config,
            $expectedMessage
        );
    }

    /**
     * @test
     * @dataProvider provideMoreInvalidConfig
     */
    public function moreInvalidTest(array $configs, \Throwable $expectedException): void
    {
        $this->expectException(get_class($expectedException));
        $this->expectExceptionMessage($expectedException->getMessage());

        $this->assertConfigurationIsValid($configs);

        $extension = $this->getExtension();

        $builder = new ContainerBuilder();
        $extension->load($configs, $builder);
    }

    public function provideInvalidConfig(): iterable
    {
        yield [
            [
                [],
            ],
            '"answear_overseas" must be configured.',
        ];

        yield [
            [
                [
                    'environment' => 'test',
                ],
            ],
            '"answear_overseas" must be configured.',
        ];

        yield [
            [
                [
                    'apiKey' => 'apiKeyString',
                ],
            ],
            'answear_overseas" must be configured.',
        ];

        yield [
            [
                [
                    'environment' => 'wrong-env',
                    'apiKey' => 'apiKeyString',
                ],
            ],
            'The value "wrong-env" is not allowed for path "answear_overseas.environment". Permissible values: "prod", "test"',
        ];
    }

    public function provideMoreInvalidConfig(): iterable
    {
        yield [
            [
                [
                    'environment' => 'prod',
                    'apiKey' => 'api-key',
                    'logger' => 'not-existing-service-name',
                ],
            ],
            new ServiceNotFoundException('not-existing-service-name'),
        ];
    }

    public function provideValidConfig(): iterable
    {
        yield [
            [
                [
                    'environment' => 'test',
                    'apiKey' => 'apiKeyString',
                ],
            ],
        ];

        yield [
            [
                [
                    'environment' => 'prod',
                    'apiKey' => 'apiKeyString',
                ],
            ],
        ];

        yield [
            [
                [
                    'environment' => 'prod',
                    'apiKey' => 8756299,
                ],
            ],
        ];
    }

    protected function getContainerExtensions(): array
    {
        return [$this->getExtension()];
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    private function getExtension(): AnswearOverseasExtension
    {
        return new AnswearOverseasExtension();
    }
}
