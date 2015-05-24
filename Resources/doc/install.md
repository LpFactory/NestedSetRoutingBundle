Installation
============

## Enable the bundle

Add the bundle as a requirement in your composer.json :

``` json
{
    "require": {
        "lpfactory/nested-set-routing-bundle": "~0.2"
    }
}
```

Enable the bundle and its dependencies :

``` php
$bundles = array(
    new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
    new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
    new LpFactory\Bundle\NestedSetRoutingBundle\LpFactoryNestedSetRoutingBundle(),
);
```

## Configure dependencies

In your `config.yml`, enable the cmf chain router and position the router provided by this bundle

``` yml
cmf_routing:
    chain:
        routers_by_id:
            lp_factory.page_dynamic_router: 200
            router.default:                 100
    dynamic:
        enabled: false
```

Enable gedmo doctrine nested set and sluggable behaviors

``` yml
stof_doctrine_extensions:
    default_locale: %locale%
    orm:
        default:
            tree: true
            sluggable: true
```

## Create entity, repository and controller

Create a Page entity implementing the NestedSetRoutingPageInterface interface :

``` yml
MyBundle\Entity\Page:
    type: entity
    table: pages
    repositoryClass: MyBundle\Entity\Repository\PageRepository
    gedmo:
        tree:
            type: nested
    fields:
        id:
            type: integer
            id: true
            generator:
                strategy: AUTO
        title:
            type: string
            length: 255
            nullable: false
        slug:
            type: string
            length: 255
            gedmo:
                slug:
                    separator: -
                    fields:
                        - title
        lft:
            type: integer
            gedmo:
                - treeLeft
        rgt:
            type: integer
            gedmo:
                - treeRight
        lvl:
            type: integer
            gedmo:
                - treeLevel
        root:
            type: integer
            nullable: true
            gedmo:
                - treeRoot
    manyToOne:
        parent:
            targetEntity: MyBundle\Entity\Page
            inversedBy: children
            joinColumn:
                name: parent_id
                referencedColumnName: id
                onDelete: CASCADE
            gedmo:
                - treeParent
    oneToMany:
        children:
            targetEntity: MyBundle\Entity\Page
            mappedBy: parent
```

Do not forget to generate the Entity class and implements the NestedSetRoutingPageInterface.

Create a repository class for this Page entity implementing the NestedSetRoutingPageRepositoryInterface :

``` php
<?php

namespace MyBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository\NestedSetRoutingPageRepositoryInterface;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;

class PageRepository extends NestedTreeRepository implements NestedSetRoutingPageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPageInTree($slug, NestedSetRoutingPageInterface $root = null)
    {
        $qb = $this
            ->createQueryBuilder('page')
            ->where('page.slug = :slug')
            ->setParameter('slug', $slug);

        if ($root !== null) {
            $qb
                ->andWhere('page.root = :root')
                ->setParameter('root', $root);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleRootNode()
    {
        return $this->getRootNodesQuery()->getSingleResult();
    }
}
```

Define this repository as a service :

``` yml
services:
    mybundle.repository.page:
        class: LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository\NestedSetRoutingPageRepositoryInterface
        factory:
            - @doctrine
            - "getRepository"
        arguments:
            - MyBundle\Entity\Page
```

Create the controller to be executed on route matching :

``` php
class PageController extends Controller
{
    public function indexAction(NestedSetRoutingPageInterface $page, $path = array())
    {
        return $this->render('MyBundle:Page:index.html.twig', array(
            'page' => $page,
            'breadcrumbs' => $path
        ));
    }
}
```

## Configure the bundle

Configure the dynamic routes provided by the bundle

``` yml
lp_factory_nested_set_routing:
    repository: mybundle.repository.page
    routes:
        view:
            prefix: 'lpfactory_page_tree_view_'
            controller: 'MyBundle:Page:index'
```

Here we define the repository used to provide page from the nested set.

This configuration catches all routes and tries to match them against the tree in your DB. If a match was found, the controller `MyBundle:Page:index` will be executed.

Create some pages and browse the tree in your browser
