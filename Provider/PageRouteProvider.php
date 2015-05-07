<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Provider;

use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\AbstractPageRouteConfiguration;
use LpFactory\Bundle\NestedSetRoutingBundle\Configuration\PageRouteConfigurationChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Factory\PageRouteFactoryInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Satinizer\UrlSatinizerChainInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Strategy\AbstractTreeStrategy;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageRouteProvider
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Routing
 * @author jobou
 */
class PageRouteProvider implements RouteProviderInterface
{
    /**
     * @var PageRouteFactoryInterface
     */
    protected $routeFactory;

    /**
     * @var PageRouteConfigurationChainInterface
     */
    protected $routeConfigurationChain;

    /**
     * @var UrlSatinizerChainInterface
     */
    protected $urlSatinizerChain;

    /**
     * @var AbstractTreeStrategy
     */
    protected $treeStrategy;

    /**
     * Constructor
     *
     * @param PageRouteFactoryInterface            $routeFactory
     * @param UrlSatinizerChainInterface           $urlSatinizerChain
     * @param PageRouteConfigurationChainInterface $routeConfigurationChain
     * @param AbstractTreeStrategy                 $treeStrategy
     */
    public function __construct(
        PageRouteFactoryInterface $routeFactory,
        UrlSatinizerChainInterface $urlSatinizerChain,
        PageRouteConfigurationChainInterface $routeConfigurationChain,
        AbstractTreeStrategy $treeStrategy
    ) {
        $this->routeFactory = $routeFactory;
        $this->urlSatinizerChain = $urlSatinizerChain;
        $this->routeConfigurationChain = $routeConfigurationChain;
        $this->treeStrategy = $treeStrategy;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $collection = new RouteCollection();

        $pathInfo = $this->urlSatinizerChain->clean($request->getPathInfo());
        $hostName = $request->getHost();

        /** @var AbstractPageRouteConfiguration $configuration */
        $configuration = $this->routeConfigurationChain->getConfigurationByPathInfo($pathInfo);
        if ($configuration === null) {
            return $collection;
        }

        // Get deepest slug
        $pagesPathInfo = $configuration->extractPathInfo($pathInfo);
        $deepestSlug = $this->treeStrategy->getDeepestPageSlug($pagesPathInfo);

        /** @var NestedSetRoutingPageInterface $page */
        foreach ($this->treeStrategy->getPage($deepestSlug, $hostName) as $page) {
            $route = $this->routeFactory->create($configuration, $page);

            // If page path matches requested uri, add route
            if ($pathInfo === $route->getPath()) {
                $collection->add(
                    $route->getName(),
                    $route
                );
                break;
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteByName($name)
    {
        /** @var AbstractPageRouteConfiguration $configuration */
        $configuration = $this->routeConfigurationChain->getConfigurationByRouteName($name);
        if ($configuration === null) {
            throw new RouteNotFoundException($name);
        }

        $pageId = $configuration->extractId($name);
        if ($pageId === null) {
            throw new RouteNotFoundException($name);
        }

        return $this->routeFactory->createFromId(
            $configuration,
            $pageId
        );
    }

    /**
     * Return empty array because routes are all dynamically loaded when needed
     *
     * {@inheritdoc}
     */
    public function getRoutesByNames($names)
    {
        return array();
    }
}
