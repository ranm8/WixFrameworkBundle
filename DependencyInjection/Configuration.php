<?php

namespace Wix\FrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wix_framework');

        $rootNode
            ->children()
                ->arrayNode('keys')
                    ->children()
                        ->scalarNode('application_key')
                            ->isRequired()
                        ->end()
                        ->scalarNode('application_secret')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
