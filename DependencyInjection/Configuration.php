<?php

declare(strict_types=1);

namespace Mpp\MessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const CONFIGURATION_ROOT = 'mpp_message';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::CONFIGURATION_ROOT);

        $treeBuilder->getRootNode()
           // TODO: Gros taf a faire ici pour que ca charge la conf du yaml défini ensemble je te laisse galèrer un peu ;)
        ;

        return $treeBuilder;
    }
}
