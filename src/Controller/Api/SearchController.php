<?php

namespace App\Controller\Api;

use App\Service\Search\EntitySearchService;
use App\Service\Search\RouteSearchService;
use App\Service\Search\SearchParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/search')]
class SearchController extends AbstractController
{
    private EntitySearchService $entitySearchService;
    private RouteSearchService $routeSearchService;
    private SearchParserService $parserService;

    public function __construct(EntitySearchService $entitySearchService, RouteSearchService $routeSearchService, SearchParserService $parserService)
    {
        $this->entitySearchService = $entitySearchService;
        $this->routeSearchService = $routeSearchService;
        $this->parserService = $parserService;
    }

    #[Route(
        name: 'app_api_search',
        options: [
            'system' => true
        ],
        defaults: [
            'description' => 'Search for anything in the application (Routes, Entities, ...)'
        ],
        methods: ['GET'],
    )]
    public function index(Request $request): Response
    {
        $query = $request->get('q');
        $query = empty($query) ? '' : $query;
        return new Response(json_encode($this->entitySearchService->findEntitiesByName($query)));
    }

    #[Route(
        '/r',
        name: 'app_api_routes',
        options: [
            'system' => true
        ],
        defaults: [
            'description' => 'Routes'
        ]
    )]
    public function routes(Request $request): Response
    {
        //return new Response(json_encode($this->routeSearchService->findAllByNameLike('curr')));
        $query = $request->get('q');
        $query = empty($query) ? '' : $query;
        return new Response(json_encode($this->parserService->parse($query)));
    }
}