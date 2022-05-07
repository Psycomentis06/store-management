<?php

namespace App\Controller\Api;

use App\Service\Search\EntitySearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    private EntitySearchService $entitySearchService;

    public function __construct(EntitySearchService $entitySearchService)
    {
        $this->entitySearchService = $entitySearchService;
    }

    #[Route(
        name: '/',
        options: [
            'system' => true
        ],
        defaults: [
            'description' => 'Search for anything in the application (Routes, Entities, ...)'
        ]
    )]
    public function index(): Response
    {
        dd($this->entitySearchService->findEntitiesByName(''));
        return new Response('Search Page');
    }
}