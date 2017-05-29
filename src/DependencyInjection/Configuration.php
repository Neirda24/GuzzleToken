<?php

namespace Guzzle\Token\DependencyInjection;

use Guzzle\Token\TokenMiddleware;
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
        $root = $treeBuilder->root('guzzle_token');

        $root
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->enumNode('type')
                        ->values(TokenMiddleware::PARAMETER_TYPES)
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('parameter')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('token')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
