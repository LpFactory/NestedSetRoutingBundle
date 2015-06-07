Configuration Reference
=======================

``` yml
lp_factory_nested_set_routing:
    repository: mybundle.repository.page
    service_ids:
        strategy: lp_factory.route_strategy.single_tree
        factory: lp_factory.route_factory
    routes:
        edit:
            prefix: 'lpfactory_page_tree_edit_'
            regex: '/(.+)?\/edit$/'
            controller: 'LpFactoryCoreBundle:Page:edit'
            path: '%s/edit'
        view:
            prefix: 'lpfactory_page_tree_view_'
            controller: 'LpFactoryCoreBundle:Page:index'
```

* `lp_factory_nested_set_routing.repository` : the repository as a service providing the page entity. 
The repository must implement NestedSetRoutingPageRepositoryInterface.
The page entity must implement NestedSetRoutingPageInterface
* `lp_factory_nested_set_routing.service_ids.strategy` : the strategy service to manage the tree 
(single tree, multiple trees in the same db, ...)
This service must extends AbstractTreeStrategy 
* `lp_factory_nested_set_routing.service_ids.factory` : the factory service building the Route object.
This service must implement PageRouteFactoryInterface

* `lp_factory_nested_set_routing.routes` : Define the configuration of the routes
    * `lp_factory_nested_set_routing.routes.prefix` : the route name prefix
    * `lp_factory_nested_set_routing.routes.regex` : the regex to be matched to apply this configuration
    * `lp_factory_nested_set_routing.routes.controller` : the controller to execute
    * `lp_factory_nested_set_routing.routes.path` : the sprintf format to generate the url
