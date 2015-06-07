Multiple routes : view, edit, ...
=================================

The configuration can be extended to provide multiple routes per page entity.

For example, we can imagine a CMS where we have 2 routes per page :

* View page : /page/child/child-of-child
* Edit page : /page/child/child-of-child/edit

To enable this strategy, modify the bundle configuration to add a route :

``` yml
lp_factory_nested_set_routing:
    repository: mybundle.repository.page
    routes:
        edit:
            prefix: 'lpfactory_page_tree_edit_'
            regex: '/(.+)?\/edit$/'
            controller: 'MyBundle:Page:edit'
            path: '%s/edit'
        view:
            prefix: 'lpfactory_page_tree_view_'
            controller: 'MyBundle:Page:index'
```

The route `edit` has 2 additional configuration keys : `regex` and `path` :

* `regex` : Define a regular expression to extract the path of slugs from the url
* `path` : Define a sprintf format to generate the url from the path of slugs
