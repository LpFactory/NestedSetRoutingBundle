<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Configuration;

/**
 * Interface PageRouteConfigurationChainInterface
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Configuration
 * @author jobou
 */
interface PageRouteConfigurationChainInterface
{
    /**
     * Add a route configuration
     *
     * @param string $alias
     * @param array  $configuration
     */
    public function add($alias, array $configuration);

    /**
     * Get a route configuration
     *
     * @param string $alias
     *
     * @return AbstractPageRouteConfiguration
     */
    public function get($alias);

    /**
     * Get all routes configuration
     *
     * @return array
     */
    public function all();

    /**
     * Check if a route configuration supports this route name
     *
     * @param string $name
     *
     * @return bool
     */
    public function supports($name);

    /**
     * Find a supported configuration for route name
     *
     * @param string $name
     *
     * @return AbstractPageRouteConfiguration|null
     */
    public function getConfigurationByRouteName($name);

    /**
     * Find a supported configuration for a path info
     *
     * @param string $pathInfo
     *
     * @return AbstractPageRouteConfiguration|null
     */
    public function getConfigurationByPathInfo($pathInfo);
}
