<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PageRouteConfiguration
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Configuration
 * @author jobou
 */
class PageRouteConfiguration extends AbstractPageRouteConfiguration
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string|null
     */
    protected $regex;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * Constructor
     *
     * @param array $routeConfiguration
     */
    public function __construct(array $routeConfiguration)
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setRequired(array(
                'prefix',
                'controller'
            ))
            ->setDefined(array(
                'regex',
                'path'
            ));

        $routeConfiguration = $resolver->resolve($routeConfiguration);

        $this->prefix = $routeConfiguration['prefix'];
        $this->controller = $routeConfiguration['controller'];
        $this->regex = isset($routeConfiguration['regex']) ? $routeConfiguration['regex'] : null;
        $this->path = isset($routeConfiguration['path']) ? $routeConfiguration['path'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * {@inheritdoc}
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}
