NestedSetRoutingBundle
======================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/LpFactory/NestedSetRoutingBundle/build-status/master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5fc430b7-2d79-4151-9f44-6b639012c697/mini.png)](https://insight.sensiolabs.com/projects/5fc430b7-2d79-4151-9f44-6b639012c697)

Cmf routing provider for Doctrine Nested Set Behavior.

# Introduction

This bundle provides a CMF router to be used with gedmo nested set behavior. You can easily build nested page with dynamic routing.

Given the following tree :

```
Homepage (slug : "homepage")
|_ Page 1 (slug : "page-1")
    |_ Child 1 (slug : "child-1")
        |_ Child 1 of 1 (slug : "child-1-of-1")
    |_ Child 2 (slug : "child-2")
```

The following routes are valid :

```
/
/page-1
/page-1/child-1
/page-1/child-1/child-1-of-1
/page-1/child-2
```

# Documentation

* [Installation](Resources/doc/install.md)
* [Reference](Resources/doc/reference.md)
* [Multiple routes : view, edit, ...](Resources/doc/multiple_routes.md)
* [Twig : generate path to page](Resources/doc/twig.md)
