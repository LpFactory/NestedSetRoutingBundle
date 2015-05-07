<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Twig;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface;
use Symfony\Bridge\Twig\Extension\RoutingExtension as BaseRoutingExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;

/**
 * Class RoutingExtension
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Twig
 * @author jobou
 */
class RoutingExtension extends BaseRoutingExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $opGenerator;

    /**
     * @var PageRouteConfigurationChainInterface
     */
    protected $routeConfiguration;

    /**
     * Constructor
     *
     * @param UrlGeneratorInterface                $generator
     * @param PageRouteConfigurationChainInterface $routeConfiguration
     */
    public function __construct(
        UrlGeneratorInterface $generator,
        PageRouteConfigurationChainInterface $routeConfiguration
    ) {
        parent::__construct($generator);

        $this->opGenerator = $generator;
        $this->routeConfiguration = $routeConfiguration;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'lp_path_page',
                array($this, 'getLpPath'),
                array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))
            )
        );
    }

    /**
     * Build nested set page route
     *
     * @param NestedSetRoutingPageInterface $page
     * @param string                        $action
     * @param array                         $parameters
     * @param bool                          $relative
     *
     * @return string
     */
    public function getLpPath(NestedSetRoutingPageInterface $page, $action, $parameters = array(), $relative = false)
    {
        $configuration = $this->routeConfiguration->get($action);

        return $this->opGenerator->generate(
            $configuration->getPageRouteName($page),
            $parameters,
            $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'lp_factory_routing_extension';
    }
}
