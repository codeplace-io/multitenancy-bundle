<?php
declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

final class CodeplaceMultitenancyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->getDefinition('Codeplace\MultitenancyBundle\EventListener\ResolveTenantListener')
            ->setArgument(0, new Reference($config['tenant_resolver']))
            ->setArgument(1, $config['tenant_reference_column_name'])
        ;
    }
}
