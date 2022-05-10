<?php

namespace App\Service\Search;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class RouteSearchService
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function findAllByNameLike(string $name): array
    {
        $res = [];
        $routes = $this->router->getRouteCollection()->all();
        foreach ($routes as $routeName => $route) {
            if (preg_match("($name)", $routeName)) $res[] = $route;
        }
        return $res;
    }
}