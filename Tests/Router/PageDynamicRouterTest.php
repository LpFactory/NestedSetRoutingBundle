<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Router;

use LpFactory\Bundle\NestedSetRoutingBundle\Tests\TestUnitPage as Page;
use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Router\PageDynamicRouter;
use Symfony\Cmf\Component\Routing\NestedMatcher\FinalMatcherInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageDynamicRouterTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Router
 * @author jobou
 */
class PageDynamicRouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getRouteCollection
     */
    public function testGetRouteCollection()
    {
        $router = $this->createPageDynamicRouter();
        $this->assertInstanceOf(
            'Symfony\Cmf\Component\Routing\LazyRouteCollection',
            $router->getRouteCollection()
        );
    }

    /**
     * Test generate
     */
    public function testGenerate()
    {
        $route = new Route('/path');

        $generator = $this
            ->getMock('Symfony\Cmf\Component\Routing\VersatileGeneratorInterface');
        $generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($route);

        $router = $this->createPageDynamicRouter(null, null, null, null, $generator);
        $this->assertEquals($route, $router->generate('test_route'));
    }

    /**
     * @expectedException Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testMatchNotFound()
    {
        $provider = $this
            ->getMock('Symfony\Cmf\Component\Routing\RouteProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getRouteCollectionForRequest')
            ->willReturn(array());
        $router = $this->createPageDynamicRouter(null, null, $provider);
        $router->matchRequest(Request::create('/path'));
    }

    /**
     * Test match and matchRequest
     */
    public function testMatch()
    {
        $routeCollection = new RouteCollection();
        $route = new Route('/path');
        $routeCollection->add('route_name', $route);

        // Route provider always return a collection with one route /path
        $provider = $this
            ->getMock('Symfony\Cmf\Component\Routing\RouteProviderInterface');
        $provider
            ->expects($this->any())
            ->method('getRouteCollectionForRequest')
            ->willReturn($routeCollection);

        // Final matcher always return string match_success
        $finalMatcher = $this
            ->getMock('Symfony\Cmf\Component\Routing\NestedMatcher\FinalMatcherInterface');
        $finalMatcher
            ->expects($this->any())
            ->method('finalMatch')
            ->willReturn('match_success');

        $router = $this->createPageDynamicRouter(null, null, $provider, $finalMatcher);
        $this->assertEquals('match_success', $router->matchRequest(Request::create('/path')));
        $this->assertEquals('match_success', $router->match('/path'));
    }

    /**
     * Test supports
     */
    public function testSupports()
    {
        $routeConfigurationChain = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
            );
        $routeConfigurationChain
            ->expects($this->once())
            ->method('supports')
            ->willReturn(true);

        $router = $this->createPageDynamicRouter(null, $routeConfigurationChain);
        $this->assertTrue($router->supports(new Page()));
        $this->assertTrue($router->supports('custom_page'));

    }

    /**
     * Test getRouteDebugMessage
     */
    public function testGetRouteDebugMessage()
    {
        $router = $this->createPageDynamicRouter();
        $this->assertEquals("Route 'route_name' not found", $router->getRouteDebugMessage('route_name'));

        $generator = $this
            ->getMock('Symfony\Cmf\Component\Routing\VersatileGeneratorInterface');
        $generator
            ->expects($this->once())
            ->method('getRouteDebugMessage')
            ->willReturn('custom_message');

        $router = $this->createPageDynamicRouter(null, null, null, null, $generator);
        $this->assertEquals("custom_message", $router->getRouteDebugMessage('route_name'));
    }

    /**
     * Test setContext getContext
     */
    public function testSetterGetter()
    {
        $context = new RequestContext();
        $router = $this->createPageDynamicRouter();
        $router->setContext($context);
        $this->assertEquals($context, $router->getContext());
    }

    /**
     * Create a PageDynamicRouter for testing
     *
     * @param RequestContext                       $context
     * @param PageRouteConfigurationChainInterface $routeConfigurationChain
     * @param RouteProviderInterface               $provider
     * @param FinalMatcherInterface                $finalMatcher
     * @param UrlGeneratorInterface                $generator
     *
     * @return PageDynamicRouter
     */
    public function createPageDynamicRouter(
        RequestContext $context = null,
        PageRouteConfigurationChainInterface $routeConfigurationChain = null,
        RouteProviderInterface $provider = null,
        FinalMatcherInterface $finalMatcher = null,
        UrlGeneratorInterface $generator = null
    ) {
        if ($context === null) {
            $context = $this
                ->getMock('Symfony\Component\Routing\RequestContext');
        }

        if ($routeConfigurationChain === null) {
            $routeConfigurationChain = $this
                ->getMock(
                    'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface'
                );
        }

        if ($provider === null) {
            $provider = $this
                ->getMock('Symfony\Cmf\Component\Routing\RouteProviderInterface');
        }

        if ($finalMatcher === null) {
            $finalMatcher = $this
                ->getMock('Symfony\Cmf\Component\Routing\NestedMatcher\FinalMatcherInterface');
        }

        if ($generator === null) {
            $generator = $this
                ->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        }

        return new PageDynamicRouter($context, $routeConfigurationChain, $provider, $finalMatcher, $generator);
    }
}
