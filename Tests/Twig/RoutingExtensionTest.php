<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Twig;

use LpFactory\Bundle\NestedSetRoutingBundle\Tests\TestUnitPage;
use LpFactory\Bundle\NestedSetRoutingBundle\Tests\RoutingHelper;
use LpFactory\Bundle\NestedSetRoutingBundle\Twig\RoutingExtension;

/**
 * Class RoutingExtensionTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Twig
 * @author jobou
 */
class RoutingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getLpPath
     */
    public function testGetLpPath()
    {
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('/path/to/page');

        $viewConfiguration = RoutingHelper::createViewConfiguration();
        $configuration = $this
            ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface');
        $configuration
            ->expects($this->once())
            ->method('get')
            ->willReturn($viewConfiguration);

        $extension = new RoutingExtension($generator, $configuration);
        $this->assertEquals('/path/to/page', $extension->getLpPath(new TestUnitPage(), 'view'));
    }

    /**
     * Test getFunctions
     */
    public function testGetFunction()
    {
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $configuration = $this
            ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface');
        $extension = new RoutingExtension($generator, $configuration);

        $functions = $extension->getFunctions();
        $this->assertEquals(1, count($functions));

        $this->assertEquals('lp_path_page', $functions[0]->getName());
        $callable = $functions[0]->getCallable();
        $this->assertTrue(method_exists($extension, $callable[1]));
    }

    /**
     * Test getName
     */
    public function testGetName()
    {
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $configuration = $this
            ->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface');
        $extension = new RoutingExtension($generator, $configuration);

        $this->assertEquals('lp_factory_routing_extension', $extension->getName());
    }
}
