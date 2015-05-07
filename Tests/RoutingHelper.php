<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfiguration;

/**
 * Class RoutingHelper
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests
 * @author jobou
 */
class RoutingHelper
{
    /**
     * Create view route configuration
     *
     * @return PageRouteConfiguration
     */
    public static function createViewConfiguration()
    {
        return new PageRouteConfiguration(
            array(
                'prefix' => 'lpfactory_page_tree_view_',
                'regex' => null,
                'controller' => 'LpFactoryNestedSetRoutingBundle:Page:index'
            )
        );
    }

    /**
     * Create edit route configuration
     *
     * @return PageRouteConfiguration
     */
    public static function createEditConfiguration()
    {
        return new PageRouteConfiguration(
            array(
                'prefix' => 'lpfactory_page_tree_edit_',
                'regex' => '/(.+)\/edit$/',
                'controller' => 'LpFactoryNestedSetRoutingBundle:Page:edit',
                'path' => '%s/edit'
            )
        );
    }
}
