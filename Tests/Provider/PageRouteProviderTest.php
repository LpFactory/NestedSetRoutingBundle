<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Provider;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Factory\PageRouteFactoryInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Provider\PageRouteProvider;
use LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlSatinizerChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Strategy\AbstractTreeStrategy;
use LpFactory\Bundle\NestedSetRoutingBundle\Tests\RoutingHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageRouteProviderTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Provider
 * @author jobou
 */
class PageRouteProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getRouteCollectionForRequest
     */
    public function testGetRouteCollectionForRequest()
    {
        // Return route view configuration on any pathinfo
        $routeConfigurationChain = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
            );
        $routeConfigurationChain
            ->expects($this->once())
            ->method('getConfigurationByPathInfo')
            ->willReturn(RoutingHelper::createViewConfiguration());

        // Route found for request (see createPageRouteProvider for injected service configuration)
        $provider = $this->createPageRouteProvider(null, null, $routeConfigurationChain, null);
        $result = $provider->getRouteCollectionForRequest(Request::create('/child1/child2'));
        $this->assertEquals(count($result), 1);
    }

    /**
     * Test getRouteCollectionForRequest
     * No configuration found
     */
    public function testGetRouteCollectionForRequestNoConfiguration()
    {
        // Check that it returns empty RouteCollection if no configuration matches
        $provider = $this->createPageRouteProvider(null, null, null, null);
        $result = $provider->getRouteCollectionForRequest(Request::create('/child1/child2'));
        $this->assertEquals(new RouteCollection(), $result);
    }

    /**
     * Test getRouteByName
     */
    public function testGetRouteByName()
    {
        // Route factory return a custom route on createFromId call
        $routeFactory = $this
            ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Factory\PageRouteFactoryInterface');
        $routeFactory
            ->expects($this->once())
            ->method('createFromId')
            ->willReturn(new Route('/path'));

        // Return route view configuration on any name
        $routeConfigurationChain = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
            );
        $routeConfigurationChain
            ->expects($this->once())
            ->method('getConfigurationByRouteName')
            ->willReturn(RoutingHelper::createViewConfiguration());

        $provider = $this->createPageRouteProvider($routeFactory, null, $routeConfigurationChain, null);
        $result = $provider->getRouteByName('lpfactory_page_tree_view_56');
        $this->assertEquals('/path', $result->getPath());
    }

    /**
     * @expectedException Symfony\Component\Routing\Exception\RouteNotFoundException
     */
    public function testGetRouteByNameNoIdInName()
    {
        // Return route view configuration on any name
        $routeConfigurationChain = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
            );
        $routeConfigurationChain
            ->expects($this->once())
            ->method('getConfigurationByRouteName')
            ->willReturn(RoutingHelper::createViewConfiguration());

        $provider = $this->createPageRouteProvider(null, null, $routeConfigurationChain, null);
        $provider->getRouteByName('test_name');
    }

    /**
     * @expectedException Symfony\Component\Routing\Exception\RouteNotFoundException
     */
    public function testGetRouteByNameNullConfiguration()
    {
        // Return null configuration on any name
        $routeConfigurationChain = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
            );
        $routeConfigurationChain
            ->expects($this->once())
            ->method('getConfigurationByRouteName')
            ->willReturn(null);

        $provider = $this->createPageRouteProvider(null, null, $routeConfigurationChain, null);
        $provider->getRouteByName('test_name');
    }

    /**
     * Test getRoutesByNames
     */
    public function testGetRoutesByNames()
    {
        $provider = $this->createPageRouteProvider();
        $this->assertEquals(array(), $provider->getRoutesByNames(array()));
    }

    /**
     * Create PageRouteProvider for testing
     *
     * @param PageRouteFactoryInterface $routeFactory
     * @param UrlSatinizerChainInterface $urlSatinizerChain
     * @param PageRouteConfigurationChainInterface $routeConfigurationChain
     * @param AbstractTreeStrategy $treeStrategy
     *
     * @return PageRouteProvider
     */
    public function createPageRouteProvider(
        PageRouteFactoryInterface $routeFactory = null,
        UrlSatinizerChainInterface $urlSatinizerChain = null,
        PageRouteConfigurationChainInterface $routeConfigurationChain = null,
        AbstractTreeStrategy $treeStrategy = null
    ) {
        // Route factory mock
        if ($routeFactory === null) {
            $route = new \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route();
            $route->setPath('/child1/child2');
            $route->setName('page_view_56');

            // Return previous created route on any call
            $routeFactory = $this
                ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Factory\PageRouteFactoryInterface');
            $routeFactory
                ->expects($this->any())
                ->method('create')
                ->willReturn($route);
        }

        // Url satinizer chain mock
        if ($urlSatinizerChain === null) {
            // Clean always return /child1/child2 path
            $urlSatinizerChain = $this
                ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlSatinizerChainInterface');
            $urlSatinizerChain
                ->expects($this->any())
                ->method('clean')
                ->willReturn('/child1/child2');
        }

        // Route configuration chain mock
        if ($routeConfigurationChain === null) {
            $routeConfigurationChain = $this
                ->getMock(
                    'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
                );
        }

        // Tree strategy mock
        if ($treeStrategy === null) {
            $page = $this->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface');
            $page
                ->expects($this->any())
                ->method('getId')
                ->willReturn(56);

            // Tree strategy return a custom page
            $treeStrategy = $this
                ->getMockBuilder('LpFactory\Bundle\NestedSetRoutingBundle\Strategy\AbstractTreeStrategy')
                ->disableOriginalConstructor()
                ->getMock();
            $treeStrategy
                ->expects($this->any())
                ->method('getPage')
                ->willReturn(array($page));
        }

        return new PageRouteProvider($routeFactory, $urlSatinizerChain, $routeConfigurationChain, $treeStrategy);
    }
}
