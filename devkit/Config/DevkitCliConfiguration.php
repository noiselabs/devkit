<?php

namespace Noiselabs\Devkit\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Devkit settings, usually loaded from a configuration file (devkit.yml).
 */
final class DevkitCliConfiguration implements ConfigurationInterface
{
    const ROOT_KEY = 'cli';

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_KEY);
        $appSettings = new AppSettings();

        $rootNode
            ->children()
            ->scalarNode('root_dir')
            ->defaultValue($appSettings->getOption('root_dir'))
            ->end()
            ->end();

        return $treeBuilder;
    }
}
