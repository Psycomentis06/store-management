<?php

namespace App\Service\Search;

use Symfony\Component\Routing\RouteCollection;

class RouteSearchService
{
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function findAllByNameLike(string $name): array
    {
        $res = [];
        $routes = $this->routeCollection->all();
        foreach ($routes as $routeName => $route) {
            if (preg_match("($name)", $routeName)) $res[] = $route;
        }
        return $res;
    }
}