<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('answear_overseas');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('environment')->isRequired()->defaultValue(null)->end()
                ->scalarNode('apiKey')->isRequired()->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
