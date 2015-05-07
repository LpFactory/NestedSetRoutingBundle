<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/LpFactory/NestedSetRoutingBundle/blob/master/LICENSE
 * @link https://github.com/LpFactory/NestedSetRoutingBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests;

use LpFactory\Bundle\NestedSetRoutingBundle\LpFactoryNestedSetRoutingBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class LpFactoryNestedSetRoutingBundleTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests
 * @author jobou
 */
class LpFactoryNestedSetRoutingBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test build
     */
    public function testBuild()
    {
        $container = new ContainerBuilder();
        $onCreatePassNb = count($container->getCompilerPassConfig()->getPasses());

        $bundle = new LpFactoryNestedSetRoutingBundle();
        $bundle->build($container);
        $this->assertEquals($onCreatePassNb + 1, count($container->getCompilerPassConfig()->getPasses()));
    }
}
