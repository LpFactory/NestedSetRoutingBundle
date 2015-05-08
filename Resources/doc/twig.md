Twig : generate path to page
============================

You can generate a path to a page in twig using the function `lp_path_page`.

If you have 2 route configurations `view` and `edit`. See related documentation : [Configure multiple routes](./multiple_routes.md)

``` twig
<a href="{{ lp_path_page(pageObject, 'view') }}">View page</a>
<a href="{{ lp_path_page(pageObject, 'edit') }}">Edit page</a>
```
