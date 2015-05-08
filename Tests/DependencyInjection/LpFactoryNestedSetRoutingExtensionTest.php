<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/LpFactory/NestedSetRoutingBundle/blob/master/LICENSE
 * @link https://github.com/LpFactory/NestedSetRoutingBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\DependencyInjection;

use LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\LpFactoryNestedSetRoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Class LpFactoryNestedSetRoutingExtensionTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\DependencyInjection
 * @author jobou
 */
class LpFactoryNestedSetRoutingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test load
     */
    public function testLoad()
    {
        $container = $this->createContainerFromFile('simple_load');

        // Routes configuration loaded
        $this->assertEquals(
            2,
            count($container->findDefinition('lp_factory.route_configuration.chain')->getMethodCalls())
        );

        // Page repository configured
        $this->assertEquals(
            'mybundle.repository.page',
            $container->findDefinition('lp_factory.route_factory')->getArgument(0)->__toString()
        );
        $this->assertEquals(
            'mybundle.repository.page',
            $container->findDefinition('lp_factory.route_strategy.abstract')->getArgument(0)->__toString()
        );

        // Strategy configured
        $this->assertEquals(
            'lp_factory.route_strategy.single_tree',
            $container->findDefinition('lp_factory.route_factory')->getArgument(1)->__toString()
        );
        $this->assertEquals(
            'lp_factory.route_strategy.single_tree',
            $container->findDefinition('lp_factory.route_provider')->getArgument(3)->__toString()
        );

        // Factory configured
        $this->assertEquals(
            'lp_factory.route_factory',
            $container->findDefinition('lp_factory.route_provider')->getArgument(0)->__toString()
        );
    }

    /**
     * Create container with the current bundle enabled
     *
     * @param array $data
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function createContainer(array $data = array())
    {
        return new ContainerBuilder(new ParameterBag(array_merge(array(
            'kernel.bundles'     => array(
                'LpFactoryNestedSetRoutingBundle' =>
                    'LpFactory\\Bundle\\NestedSetRoutingBundle\\LpFactoryNestedSetRoutingBundle'
            ),
            'kernel.cache_dir'   => __DIR__,
            'kernel.debug'       => false,
            'kernel.environment' => 'test',
            'kernel.name'        => 'kernel',
            'kernel.root_dir'    => __DIR__,
        ), $data)));
    }

    /**
     * Register a configuration file
     *
     * @param string $file
     * @param array $data
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function createContainerFromFile($file, $data = array())
    {
        $container = $this->createContainer($data);
        $container->registerExtension(new LpFactoryNestedSetRoutingExtension());

        $this->loadFromFile($container, $file);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }

    /**
     * Load file in container
     *
     * @param ContainerBuilder $container
     * @param string           $file
     */
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load($file.'.yml');
    }
}
