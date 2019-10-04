<?php

namespace Nicosomb\WallabagWebarchiveBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wallabag_webarchive');

        $rootNode
            ->children()
                ->scalarNode('enabled')
                    ->defaultValue('true')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
