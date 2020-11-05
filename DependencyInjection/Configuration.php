<?php

declare(strict_types=1);

namespace Mpp\MessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const CONFIGURATION_ROOT = 'mpp_message';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIGURATION_ROOT);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('messages')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('from')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('subject')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('template_txt')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('template_html')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('attachments')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('file')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('mime_type')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
