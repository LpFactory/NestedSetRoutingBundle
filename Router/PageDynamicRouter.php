<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Router;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;
use Symfony\Cmf\Component\Routing\ChainedRouterInterface;
use Symfony\Cmf\Component\Routing\LazyRouteCollection;
use Symfony\Cmf\Component\Routing\NestedMatcher\FinalMatcherInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Cmf\Component\Routing\VersatileGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageDynamicRouter
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Router
 * @author jobou
 */
class PageDynamicRouter implements ChainedRouterInterface, RequestMatcherInterface
{
    /**
     * @var RequestContext
     */
    protected $context;

    /**
     * @var PageRouteConfigurationChainInterface
     */
    protected $routeConfigurationChain;

    /**
     * @var RouteProviderInterface
     */
    protected $provider;

    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @var FinalMatcherInterface
     */
    protected $finalMatcher;

    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * Constructor
     *
     * @param RequestContext                       $context
     * @param PageRouteConfigurationChainInterface $routeConfigurationChain
     * @param RouteProviderInterface               $provider
     * @param FinalMatcherInterface                $finalMatcher
     * @param UrlGeneratorInterface                $generator
     */
    public function __construct(
        RequestContext $context,
        PageRouteConfigurationChainInterface $routeConfigurationChain,
        RouteProviderInterface $provider,
        FinalMatcherInterface $finalMatcher,
        UrlGeneratorInterface $generator
    ) {
        $this->routeConfigurationChain = $routeConfigurationChain;
        $this->provider = $provider;
        $this->finalMatcher = $finalMatcher;
        $this->generator = $generator;

        $this->generator->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        if (!$this->routeCollection instanceof RouteCollection) {
            $this->routeCollection = new LazyRouteCollection($this->provider);
        }

        return $this->routeCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        return $this->generator->generate(
            $name,
            $parameters,
            $referenceType ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathInfo)
    {
        // This router works only with request matching
        $request = Request::create($pathInfo);

        return $this->matchRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $collection = $this->provider->getRouteCollectionForRequest($request);
        if (!count($collection)) {
            throw new ResourceNotFoundException();
        }

        return $this->finalMatcher->finalMatch($collection, $request);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name)
    {
        // This router supports all NestedSetRoutingPageInterface object
        if ($name instanceof NestedSetRoutingPageInterface) {
            return true;
        }

        // Check if the route name matches one from a route configuration
        return $this->routeConfigurationChain->supports($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteDebugMessage($name, array $parameters = array())
    {
        if ($this->generator instanceof VersatileGeneratorInterface) {
            return $this->generator->getRouteDebugMessage($name, $parameters);
        }

        return "Route '$name' not found";
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }
}
