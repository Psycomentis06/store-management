<?php

namespace App\Service\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;

class SearchParserService
{
    private EntityManagerInterface $entityManager;
    private EntitySearchService $entitySearchService;

    public function __construct(EntityManagerInterface $entityManager, EntitySearchService $entitySearchService)
    {
        $this->entityManager = $entityManager;
        $this->entitySearchService = $entitySearchService;
    }

    public function parse(string $query)
    {
        switch ($query) {
            case str_starts_with($query, '/e '):
                $this->parseEntity($query);
                break;
            default:
                throw new \InvalidArgumentException("Invalid scope for '$query'");
        }
    }

    /**
     * @throws EntityNotFoundException
     */
    public function parseEntity(string $query)
    {
        // String format should be : /e entityName:field value
        $query = trim($query, ' ');
        $query = explode(' ', $query);
        $options = [
            'scope' => $query[0],
            'entity' => $query[1],
            'value' => $query[2]
        ];

        if (!str_starts_with($options['scope'], '/e')) {
            throw new \ParseError('Entity scope shall start with \'/e\' ');
        }

        $entityParams = explode(':', $options['entity']);
        if (count($entityParams) === 2) {
            // Entity name passed with field to search by exp: product:name
            $options['entity'] = [
                'name' => $entityParams[0],
                'field' => $entityParams[1]
            ];
        }

        $entity = [];
        if (isset($options['entity']['name'])) {
            $entity = $this->entitySearchService->findEntityByName($options['entity']['name']);
        } else {
            $entity = $this->entitySearchService->findEntityByName($options['entity']);
        }

        if (!empty($entity)) {
            $entity = $this->entitySearchService->getEntityClassNamespace($entity);
            $field = null;
            if (isset($options['entity']['field']))
                $field = $options['entity']['field'];
            $this->createEntityQueryBuilder($entity, $field, $options['value']);

        } else {
            throw new EntityNotFoundException("Entity Not Found");
        }

    }

    public function createEntityQueryBuilder(string $entityName, ?string $field, string $query): ?QueryBuilder
    {
        $entityMeta = $this->entityManager->getClassMetadata($entityName);
        $repo = $this->entityManager->getRepository($entityName);

        $queryBuilder = $repo->createQueryBuilder($entityMeta->getTableName()[0]);
        if (!empty($field)) {
            $fieldExists = $this->entitySearchService->fieldExists($entityName, $field);
            dd($fieldExists);
        }
        return null;
    }

    public function createEntityQueryBuilderByGuess(string $query)
    {
    }
}