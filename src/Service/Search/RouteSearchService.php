<?php

namespace App\Service\Search;

use Symfony\Component\Routing\RouterInterface;

class RouteSearchService
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function findAllByNameLike(string $name = "", bool $associative = true): array
    {
        $routes = $this->router->getRouteCollection()->all();
        if ($associative) {
            return array_filter($routes, function ($key) use ($name) {
                return preg_match("($name)", $key);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            $res = [];
            foreach ($routes as $routeName => $route) {
                if (preg_match("($name)", $routeName)) $res[] = $route;
            }
            return $res;
        }
    }
}