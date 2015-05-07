<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/LpFactory/NestedSetRoutingBundle/blob/master/LICENSE
 * @link https://github.com/LpFactory/NestedSetRoutingBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\DependencyInjection\Compiler;

use LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\Compiler\UrlSatinizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class UrlSatinizerPassTest
 *
 * @author jobou
 */
class UrlSatinizerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test process return null
     */
    public function testProcessNoDefinition()
    {
        $container = new ContainerBuilder();
        $urlSatinizerPass = new UrlSatinizerPass();
        $this->assertNull($urlSatinizerPass->process($container));
    }

    /**
     * Test process items
     */
    public function testProcessItems()
    {
        $definition = new Definition();

        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerMock
            ->expects($this->once())
            ->method('hasDefinition')
            ->willReturn(true);

        $containerMock
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        $containerMock
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn(array('id1' => 'reference1', 'id2' => 'reference2'));

        $urlSatinizerPass = new UrlSatinizerPass();
        $urlSatinizerPass->process($containerMock);
        $this->assertEquals(2, count($definition->getMethodCalls()));
    }
}
