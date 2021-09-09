<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\DependencyInjection;

use Answear\OverseasBundle\Enum\EnvironmentEnum;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('answear_overseas');

        $treeBuilder->getRootNode()
            ->children()
                ->enumNode('environment')
                    ->values(EnvironmentEnum::getValues())
                ->isRequired()
                ->end()
                ->scalarNode('apiKey')->isRequired()->end()
                ->scalarNode('logger')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
