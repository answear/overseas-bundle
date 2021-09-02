<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\DependencyInjection;

use Answear\OverseasBundle\ConfigProvider;
use Answear\OverseasBundle\Enum\EnvironmentEnum;
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

        $definition = $container->getDefinition(ConfigProvider::class);
        $definition->setArguments(
            [
                EnvironmentEnum::get($config['environment']),
                $config['apiKey'],
            ]
        );
    }

    private function validateConfig(array $config): void
    {
        EnvironmentEnum::get($config['environment']);

        if (empty($config['apiKey']) || !is_string($config['apiKey'])) {
            throw new \InvalidArgumentException(
                'Provide valid apiKey config.'
            );
        }
    }
}
