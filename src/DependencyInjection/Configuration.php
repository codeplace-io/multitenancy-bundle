<?php
declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('codeplace_multitenancy');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('tenant_resolver')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('tenant_reference_column_name')
                    ->defaultValue('tenant_id')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}