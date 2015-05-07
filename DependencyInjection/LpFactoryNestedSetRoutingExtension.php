<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/OpSiteBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/OpSiteBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class LpFactoryNestedSetRoutingExtension
 *
 * @author jobou
 */
class LpFactoryNestedSetRoutingExtension extends Extension
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
    }
}
