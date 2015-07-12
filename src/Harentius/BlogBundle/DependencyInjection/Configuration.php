<?php

namespace Harentius\BlogBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('harentius_blog');

        $rootNode->children()
            ->arrayNode('sidebar')
                ->children()
                    ->scalarNode('cache_filefime')->defaultValue(0)->end()
                    ->integerNode('tags_limit')->defaultValue(10)->end()
                    ->arrayNode('tag_sizes')
                        ->prototype('integer')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('homepage')
                ->children()
                    ->scalarNode('page_slug')->defaultValue(null)->end()
                ->end()
            ->end()
            ->arrayNode('list')
                ->children()
                    ->integerNode('posts_per_page')->defaultValue(10)->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
