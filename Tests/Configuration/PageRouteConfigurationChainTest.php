<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests\Configuration;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\AbstractPageRouteConfiguration;
use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChain;

/**
 * Class PageRouteConfigurationChainTest
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests\Configuration
 * @author jobou
 */
class PageRouteConfigurationChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageRouteConfigurationChain
     */
    protected $configurationChain;

    /**
     * Set up a route configuration chain
     */
    public function setUp()
    {
        $this->configurationChain = new PageRouteConfigurationChain(
            'LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfiguration'
        );

        $this->configurationChain->add(
            'edit',
            array(
                'prefix' => 'lpfactory_page_tree_edit_',
                'regex' => '/(.+)\/edit$/',
                'controller' => 'LpFactoryNestedSetRoutingBundle:Page:edit',
                'path' => '%s/edit'
            )
        );

        $this->configurationChain->add(
            'view',
            array(
                'prefix' => 'lpfactory_page_tree_view_',
                'regex' => null,
                'controller' => 'LpFactoryNestedSetRoutingBundle:Page:index'
            )
        );
    }

    /**
     * Test unknown configuration
     *
     * @expectedException \LogicException
     */
    public function testUnknownConfiguration()
    {
        $this->configurationChain->get('test');
    }

    /**
     * Test configuration getter
     */
    public function testConfigurationGetter()
    {
        $configuration = $this->configurationChain->get('view');
        $this->validateViewConfiguration($configuration);

        $configuration = $this->configurationChain->getConfigurationByRouteName('lpfactory_page_tree_view_56');
        $this->validateViewConfiguration($configuration);
        $configuration = $this->configurationChain->getConfigurationByRouteName('lpfactory_page_tree_remove_56');
        $this->assertNull($configuration);

        $configuration = $this->configurationChain->getConfigurationByPathInfo('/child1/child2');
        $this->validateViewConfiguration($configuration);
    }

    /**
     * Test supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->configurationChain->supports('lpfactory_page_tree_view_56'));
        $this->assertFalse($this->configurationChain->supports('lpfactory_page_tree_remove_56'));
    }

    /**
     * Validate it is a view configuration
     *
     * @param AbstractPageRouteConfiguration $configuration
     */
    protected function validateViewConfiguration(AbstractPageRouteConfiguration $configuration)
    {
        $this->assertEquals($configuration->getPath(), null);
        $this->assertEquals($configuration->getPrefix(), 'lpfactory_page_tree_view_');
        $this->assertEquals($configuration->getRegex(), null);
        $this->assertEquals($configuration->getController(), 'LpFactoryNestedSetRoutingBundle:Page:index');
    }

    /**
     * Test getIterator
     */
    public function testGetIterator()
    {
        $this->assertInstanceOf('\ArrayIterator', $this->configurationChain->getIterator());
    }
}
