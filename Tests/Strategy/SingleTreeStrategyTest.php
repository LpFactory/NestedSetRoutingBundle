<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Strategy;

use LpFactory\Bundle\NestedSetRoutingBundle\Tests\TestUnitPage as Page;
use LpFactory\Bundle\NestedSetRoutingBundle\Strategy\SingleTreeStrategy;

/**
 * Class SingleTreeStrategyTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Strategy
 * @author jobou
 */
class SingleTreeStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test isHomeTreeRoot
     */
    public function testIsHomeTreeRoot()
    {
        $repository = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository\NestedSetRoutingPageRepositoryInterface'
            );

        $strategy = new SingleTreeStrategy($repository, true);
        $this->assertTrue($strategy->isHomeTreeRoot());

        $strategy = new SingleTreeStrategy($repository, false);
        $this->assertFalse($strategy->isHomeTreeRoot());
    }

    /**
     * Test getPage
     */
    public function testGetPage()
    {
        // Root node for getSingleRootNode method
        $rootPage = new Page();
        $rootPage
            ->setSlug('home');

        // Page node for getPageInTree method
        $page = new Page();
        $page
            ->setSlug('child-page');

        $repository = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository\NestedSetRoutingPageRepositoryInterface'
            );
        $repository
            ->expects($this->any())
            ->method('getSingleRootNode')
            ->willReturn($rootPage);
        $repository
            ->expects($this->any())
            ->method('getPageInTree')
            ->willReturn($page);

        $strategy = new SingleTreeStrategy($repository, true);

        // Test that strategy return built root node
        $this->assertEquals($rootPage, $strategy->getRootNode('LpFactory.localhost'));

        // Homepage test. Return root node
        $result = $strategy->getPage("", "LpFactory.localhost");
        $this->assertEquals(array($rootPage), $result);

        // Page node found from slug
        $result = $strategy->getPage('child-page', "LpFactory.localhost");
        $this->assertEquals($page, $result);
    }

    /**
     * Test getDeepestPageSlug
     */
    public function testGetDeepestPageSlug()
    {
        $repository = $this
            ->getMock(
                'LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository\NestedSetRoutingPageRepositoryInterface'
            );
        $strategy = new SingleTreeStrategy($repository, true);

        $this->assertEquals('deepest-node', $strategy->getDeepestPageSlug('/test/child/deepest-node'));
        $this->assertEquals('', $strategy->getDeepestPageSlug('/'));
    }
}
