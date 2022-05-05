<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActiveRouteExtension extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $request)
    {
        $this->requestStack = $request;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_route_active', [$this, 'isRouteActive']),
        ];
    }

    public function isRouteActive(string $routeName): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($routeName == $request->get('_route')) return 'active';
        return '';
    }
}
