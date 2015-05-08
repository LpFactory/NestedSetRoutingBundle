<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/LpFactory/NestedSetRoutingBundle/blob/master/LICENSE
 * @link https://github.com/LpFactory/NestedSetRoutingBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\DependencyInjection;

use LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\DependencyInjection
 * @author jobou
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getConfigTreeBuilder
     */
    public function testGetConfigTreeBuilder()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(array('repository' => 'test')));
        $this->assertEquals(
            $config,
            array(
                'repository' => 'test',
                'routes' => array()
            )
        );
    }

    /**
     * Test valid configuration
     *
     * @dataProvider getRouteData
     */
    public function testGetConfigTreeBuilderValid($routeConfig, $processedRouteConfig)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(
            array(
                'repository' => 'test',
                'routes' => $routeConfig
            )
        ));

        $this->assertEquals($config['routes'], $processedRouteConfig);
    }

    /**
     * Provider for testGetConfigTreeBuilderValid
     *
     * @return array
     */
    public function getRouteData()
    {
        return array(
            array(
                array(
                    'view' => array(
                        'prefix' => 'lpfactory_page_tree_view_',
                        'controller' => 'LpFactoryCoreBundle:Page:index'
                    )
                ),
                array(
                    'view' => array(
                        'prefix' => 'lpfactory_page_tree_view_',
                        'controller' => 'LpFactoryCoreBundle:Page:index',
                        'regex' => null,
                        'path' => null
                    )
                )
            ),
            array(
                array(
                    'view' => array(
                        'prefix' => 'lpfactory_page_tree_view_',
                        'controller' => 'LpFactoryCoreBundle:Page:index'
                    ),
                    'edit' => array(
                        'prefix' => 'lpfactory_page_tree_edit_',
                        'regex' => '/(.+)\/edit$/',
                        'controller' => 'LpFactoryCoreBundle:Page:edit',
                        'path' => '%s/edit'
                    )
                ),
                array(
                    'view' => array(
                        'prefix' => 'lpfactory_page_tree_view_',
                        'controller' => 'LpFactoryCoreBundle:Page:index',
                        'regex' => null,
                        'path' => null
                    ),
                    'edit' => array(
                        'prefix' => 'lpfactory_page_tree_edit_',
                        'regex' => '/(.+)\/edit$/',
                        'controller' => 'LpFactoryCoreBundle:Page:edit',
                        'path' => '%s/edit'
                    )
                )
            )
        );
    }

    /**
     * Test invalid configuration
     *
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testGetConfigTreeBuilderInvalid()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(
            'routes' => array(
                'test' => array(
                    'toto' => 'unknown value'
                )
            )
        ));
    }
}
