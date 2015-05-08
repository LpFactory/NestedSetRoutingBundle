<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/LpFactory/NestedSetRoutingBundle/blob/master/LICENSE
 * @link https://github.com/LpFactory/NestedSetRoutingBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class LpFactoryNestedSetRoutingExtension
 *
 * @author jobou
 */
class LpFactoryNestedSetRoutingExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('entities.yml');
        $loader->load('cmf_routing.yml');
        $loader->load('twig.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->loadConfiguration($config, $container);
    }

    /**
     * Load configuration in container
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function loadConfiguration(array $config, ContainerBuilder $container)
    {
        // Load page nested routes
        $routeConfigurationChain = $container->findDefinition('lp_factory.route_configuration.chain');
        foreach ($config['routes'] as $alias => $routeConfiguration) {
            $routeConfigurationChain->addMethodCall('add', array($alias, $routeConfiguration));
        }

        // Add repository argument
        $routeFactory = $container->findDefinition('lp_factory.route_factory');
        $routeFactory->replaceArgument(0, new Reference($config['repository']));
        $abstractStrategy = $container->findDefinition('lp_factory.route_strategy.abstract');
        $abstractStrategy->replaceArgument(0, new Reference($config['repository']));
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // Enable the useful doctrine extension for this bundle
        $container->prependExtensionConfig(
            'stof_doctrine_extensions',
            array(
                'orm' => array(
                    'default' => array(
                        'tree'      => true,
                        'sluggable' => true
                    )
                )
            )
        );

        // Enable the useful doctrine extension for this bundle
        $container->prependExtensionConfig(
            'cmf_routing',
            array(
                'dynamic' => array(
                    'enabled' => false
                )
            )
        );
    }
}
