<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Satinizer;

use LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlFormatSatinizer;
use LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlSatinizerChain;

/**
 * Class UrlSatinizerChainTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Satinizer
 * @author jobou
 */
class UrlSatinizerChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test clean
     */
    public function testClean()
    {
        $chain = new UrlSatinizerChain();

        $satinizerMock = $this->getMock('LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlSatinizerInterface');
        $satinizerMock
            ->expects($this->once())
            ->method('clean')
            ->willReturn('/child1/child2/child3.json');

        $chain->addSatinizer($satinizerMock);
        $chain->addSatinizer(new UrlFormatSatinizer());

        $this->assertEquals('/child1/child2/child3', $chain->clean('/child1/child2/child3.json#test'));
    }
}
