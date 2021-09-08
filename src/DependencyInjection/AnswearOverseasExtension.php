<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\DependencyInjection;

use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\Enum\EnvironmentEnum;
use Answear\OverseasBundle\Logger\OverseasLogger;
use Psr\Log\NullLogger;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AnswearOverseasExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $this->validateConfig($config);

        $this->setConfigProvider($container, EnvironmentEnum::get($config['environment']), $config['apiKey']);
        $this->setLogger($container, $config['logger'] ?? null);
    }

    private function setConfigProvider(ContainerBuilder $container, EnvironmentEnum $environment, string $apiKey): void
    {
        $definition = $container->getDefinition(ConfigProvider::class);
        $definition->setArguments([$environment, $apiKey]);
    }

    private function setLogger(ContainerBuilder $container, ?string $loggerClassName): void
    {
        $definition = $container->getDefinition(OverseasLogger::class);
        $definition->setArguments(
            [
                null !== $loggerClassName
                    ? $container->getDefinition($loggerClassName)
                    : new NullLogger(),
            ]
        );
    }

    private function validateConfig(array $config): void
    {
        //check if enum exists
        EnvironmentEnum::get($config['environment']);

        if (empty($config['apiKey']) || !is_string($config['apiKey'])) {
            throw new \InvalidArgumentException(
                'Provide valid apiKey config.'
            );
        }
    }
}
