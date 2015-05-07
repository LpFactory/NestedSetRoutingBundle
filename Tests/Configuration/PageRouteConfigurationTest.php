<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Configuration;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfiguration;
use LpFactory\Bundle\NestedSetRoutingBundle\Tests\RoutingHelper;

/**
 * Class PageRouteConfigurationTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Configuration
 * @author jobou
 */
class PageRouteConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test isMatching method
     *
     * @dataProvider isMatchingProvider
     *
     * @param array  $configuration
     * @param string $url
     * @param bool   $result
     */
    public function testIsMatching(array $configuration, $url, $result)
    {
        $routeConfiguration = new PageRouteConfiguration($configuration);
        $this->assertEquals($result, $routeConfiguration->isMatching($url));
    }

    /**
     * @return array
     */
    public function isMatchingProvider()
    {
        return array(
            array(
                array('prefix' => 'test', 'controller' => 'test', 'regex' => null),
                '/child1/child2',
                true
            ),
            array(
                array('prefix' => 'test', 'controller' => 'test', 'regex' => '/(.+)\/edit$/'),
                '/child1/child2/edit',
                true
            ),
            array(
                array('prefix' => 'test', 'controller' => 'test', 'regex' => '/(.+)\/edit$/'),
                '/child1/child2/editing',
                false
            )
        );
    }

    /**
     * Test extractPathInfo method
     *
     * @dataProvider extractPathInfoProvider
     *
     * @param array  $configuration
     * @param string $url
     * @param string $result
     */
    public function testExtractPathInfo(array $configuration, $url, $result)
    {
        $routeConfiguration = new PageRouteConfiguration($configuration);
        $this->assertEquals($result, $routeConfiguration->extractPathInfo($url));
    }

    /**
     * @return array
     */
    public function extractPathInfoProvider()
    {
        return array(
            array(
                array('prefix' => 'test', 'controller' => 'test', 'regex' => null),
                '/child1/child2',
                '/child1/child2'
            ),
            array(
                array('prefix' => 'test', 'controller' => 'test', 'regex' => '/(.+)\/edit$/'),
                '/child1/child2/edit',
                '/child1/child2'
            )
        );
    }

    /**
     * Test extractId method
     *
     * @dataProvider extractIdProvider
     *
     * @param array  $configuration
     * @param string $name
     * @param string $result
     */
    public function testExtractId(array $configuration, $name, $result)
    {
        $routeConfiguration = new PageRouteConfiguration($configuration);
        $this->assertEquals($result, $routeConfiguration->extractId($name));
    }

    /**
     * @return array
     */
    public function extractIdProvider()
    {
        return array(
            array(
                array('prefix' => 'lpfactory_page_view_', 'controller' => 'test'),
                'lpfactory_page_view_56',
                '56'
            ),
            array(
                array('prefix' => 'lpfactory_page_view_', 'controller' => 'test'),
                'lpfactory_page_edit_56',
                null
            )
        );
    }

    /**
     * Test buildPath method
     *
     * @dataProvider buildPathProvider
     *
     * @param array  $configuration
     * @param string $pathInfo
     * @param string $result
     */
    public function testBuildPath(array $configuration, $pathInfo, $result)
    {
        $routeConfiguration = new PageRouteConfiguration($configuration);
        $this->assertEquals($result, $routeConfiguration->buildPath($pathInfo));
    }

    /**
     * @return array
     */
    public function buildPathProvider()
    {
        return array(
            array(
                array('prefix' => 'test', 'controller' => 'test', 'path' => null),
                '/child1/child2',
                '/child1/child2'
            ),
            array(
                array('prefix' => 'test', 'controller' => 'test', 'path' => '%s/edit'),
                '/child1/child2',
                '/child1/child2/edit'
            )
        );
    }

    /**
     * Test method getPageRouteName
     */
    public function testGetPageRouteName()
    {
        $routeConfiguration = new PageRouteConfiguration(
            array('prefix' => 'lpfactory_page_view_', 'controller' => 'test')
        );

        $page = $this
            ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface');

        $page
            ->expects($this->any())
            ->method('getId')
            ->willReturn(56);

        $this->assertEquals('lpfactory_page_view_56', $routeConfiguration->getPageRouteName($page));
    }

    /**
     * Test getter
     *
     * @expectedException Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     */
    public function testGetter()
    {
        $routeConfiguration = RoutingHelper::createEditConfiguration();

        $this->assertEquals('lpfactory_page_tree_edit_', $routeConfiguration->getPrefix());
        $this->assertEquals('/(.+)\/edit$/', $routeConfiguration->getRegex());
        $this->assertEquals('LpFactoryNestedSetRoutingBundle:Page:edit', $routeConfiguration->getController());
        $this->assertEquals('%s/edit', $routeConfiguration->getPath());

        // Launch exception
        $routeConfiguration = new PageRouteConfiguration(array('unknown' => 'value'));
    }
}
