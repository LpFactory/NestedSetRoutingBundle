<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Configuration;

use IteratorAggregate;
use ArrayIterator;

/**
 * Class PageRouteConfigurationChain
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Configuration
 * @author jobou
 */
class PageRouteConfigurationChain implements PageRouteConfigurationChainInterface, IteratorAggregate
{
    /**
     * @var string
     */
    protected $routeConfigurationClass;

    /**
     * @var array
     */
    protected $configurations = array();

    /**
     * Constructor
     *
     * @param string $routeConfigurationClass
     */
    public function __construct($routeConfigurationClass)
    {
        $this->routeConfigurationClass = $routeConfigurationClass;
    }

    /**
     * {@inheritdoc}
     */
    public function add($alias, array $configuration)
    {
        $routeConfiguration = new $this->routeConfigurationClass($configuration);

        $this->configurations[$alias] = $routeConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function get($alias)
    {
        if (!isset($this->configurations[$alias])) {
            throw new \LogicException('No route configuration for alias : '.$alias);
        }

        return $this->configurations[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->configurations;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name)
    {
        /** @var AbstractPageRouteConfiguration $configuration */
        foreach ($this->all() as $configuration) {
            if ($configuration->supports($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationByRouteName($name)
    {
        return $this->findConfiguration($name, function (AbstractPageRouteConfiguration $configuration, $name) {
            return $configuration->supports($name);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationByPathInfo($name)
    {
        return $this->findConfiguration($name, function (AbstractPageRouteConfiguration $configuration, $name) {
            return $configuration->isMatching($name);
        });
    }

    /**
     * Find a configuration matching custom criteria
     *
     * @param string    $value
     * @param callable $func
     *
     * @return null|AbstractPageRouteConfiguration
     */
    protected function findConfiguration($value, \Closure $func)
    {
        /** @var AbstractPageRouteConfiguration $configuration */
        foreach ($this->all() as $configuration) {
            if ($func($configuration, $value)) {
                return $configuration;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->configurations);
    }
}
