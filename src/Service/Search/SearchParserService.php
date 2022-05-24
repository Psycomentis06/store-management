<?php

namespace App\Service\Search;

use App\Utils\LikeQueryHelpers;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class SearchParserService
{
    use LikeQueryHelpers;

    private EntityManagerInterface $entityManager;
    private EntitySearchService $entitySearchService;
    private RequestStack $requestStack;
    private RouteSearchService $routeSearchService;

    public function __construct(EntityManagerInterface $entityManager, EntitySearchService $entitySearchService, RequestStack $requestStack, RouteSearchService $routeSearchService)
    {
        $this->entityManager = $entityManager;
        $this->entitySearchService = $entitySearchService;
        $this->requestStack = $requestStack;
        $this->routeSearchService = $routeSearchService;
    }

    /**
     * @throws EntityNotFoundException
     * @throws \ReflectionException
     */
    public function parse(string $query): array
    {
        if (str_starts_with($query, '/e ')) {
            return $this->entityResultToApiResponse($this->parseEntity($query));
        } else if (str_starts_with($query, '/r')) {
            return $this->routeResultToApiResponse($this->parseRoute($query));
        } else {
            return $this->entityResultToApiResponse($this->createEntityQueryBuilderByGuess($query));
        }
    }

    #[ArrayShape(['content' => "array"])]
    public function routeResultToApiResponse(array $data)
    {
        return [
            'content' => $data
        ];
    }

    #[ArrayShape(['content' => "array", 'redirectUrl' => "mixed"])]
    public function entityResultToApiResponse(array $data): array
{
        $res = [
            'content' => []
        ];
        $content = array();
        if (!empty($data['res'])) {
            foreach ($data['res'] as $entity) {
                $content[] = [
                    "id" => $entity->getId(),
                    "title" => $entity->getSearchCardTitle(),
                    "body" => $entity->getSearchCardBody(),
                    "image" => $entity->getSearchCardImage()
                ];
            }
            $res['content'] = $content;
        }
        if (!empty($data['redirectUrl'])) {
            $res['redirectUrl'] = $data['redirectUrl'];
        }
        return $res;
    }

    /**
     * @throws EntityNotFoundException
     * @throws \ReflectionException
     */
    public function parseEntity(string $query): array
    {
        // String format should be : /e entityName:field value
        $query = trim($query, ' ');
        $query = explode(' ', $query);
        $options = [
            'scope' => $query[0],
            'entity' => $query[1],
            'value' => join(' ', array_slice($query, 2, count($query) - 1))
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
            $entity = $this->entitySearchService->findSearchableEntityByName($options['entity']['name']);
        } else {
            $entity = $this->entitySearchService->findSearchableEntityByName($options['entity']);
        }

        if (!empty($entity)) {
            $entitySimpleName = $entity;
            $entity = $this->entitySearchService->getEntityClassNamespace($entity);
            $field = null;
            if (isset($options['entity']['field']))
                $field = $options['entity']['field'];

            $maxResult = $this->requestStack->getCurrentRequest()->get('max');
            if (empty($maxResult)) $maxResult = 10;
            $res = $this->createEntityQueryBuilder($entity, $field, $options['value']);
            $res->setMaxResults($maxResult)
                ->getQuery()
                ->getResult(AbstractQuery::HYDRATE_OBJECT);
            return ["res" => $res, "redirectUrl" => $this->routeSearchService->getEntityIndexRoutePath($entitySimpleName)];
        } else {
            throw new EntityNotFoundException("Entity Not Found");
        }
    }

    public function createEntityQueryBuilder(string $entityName, ?string $field, string $query): QueryBuilder
    {
        $entityMeta = $this->entityManager->getClassMetadata($entityName);
        $repo = $this->entityManager->getRepository($entityName);
        $queryAlias = $entityMeta->getTableName()[0];
        $queryBuilder = $repo->createQueryBuilder($queryAlias);
        $queryParams = [];
        if (!empty($field)) {
            $fieldExists = $this->entitySearchService->fieldExists($entityName, $field);
            $queryParams = [
                'alias' => $queryAlias,
                'query' => $query
            ];
            if ($fieldExists) {
                $queryParams['field'] = $field;
            } else {
                $queryParams['field'] = $entityName::getDefaultSearchFieldName();
            }
        } else {
            // Method getDefaultSearchFieldName() came from SearchableEntityInterface
            $defaultField = $entityName::getDefaultSearchFieldName();
            $queryParams = [
                'alias' => $queryAlias,
                'field' => $defaultField,
                'query' => $query
            ];
        }

        // Build Where condition based on value type
        //dd($this->entitySearchService->getFieldType($entityName, $queryParams['field']));
        $fieldType = $this->entitySearchService->getFieldType($entityName, $queryParams['field']);
        switch ($fieldType) {
            case 'string':
                $queryBuilder
                    ->where($queryParams['alias'] . '.' . $queryParams['field'] . ' like :' . $queryParams['field'])
                    ->setParameter($queryParams['field'], $this->makeLikeParam($queryParams['query']));
                break;
            case 'integer':
                $queryBuilder
                    ->where($queryParams['alias'] . '.' . $queryParams['field'] . ' = :' . $queryParams['field'])
                    ->setParameter($queryParams['field'], $queryParams['query']);
                break;
            case 'date':
                $queryDateValues = explode(' ', trim($queryParams['query']));
                if (empty($queryDateValues) || (count($queryDateValues) === 1 && empty($queryDateValues[0])))
                    $queryDateValues = array();

                //dd($queryDateValuesCount);
                switch (count($queryDateValues)) {
                    case 0:
                        $queryBuilder
                            ->where(
                                $queryParams['alias'] . '.' . $queryParams['field'] . ' = :' . $queryParams['field']
                            )
                            ->setParameter($queryParams['field'], 'now');
                        break;
                    case 1:
                        $queryBuilder
                            ->where(
                                $queryParams['alias'] . '.' . $queryParams['field'] . ' between :' . $queryParams['field'] . '_1 and :' . $queryParams['field'] . '_2'
                            )
                            ->setParameter($queryParams['field'] . '_1', $queryDateValues[0])
                            ->setParameter($queryParams['field'] . '_2', 'now()');
                        break;
                    default:
                        $queryBuilder
                            ->where(
                                $queryParams['alias'] . '.' . $queryParams['field'] . ' between :' . $queryParams['field'] . '_1 and :' . $queryParams['field'] . '_2'
                            )
                            ->setParameter($queryParams['field'] . '_1', $queryDateValues[0])
                            ->setParameter($queryParams['field'] . '_2', $queryDateValues[1]);
                        break;
                }
                break;
            default:
                throw new UnsupportedException('\'' . $fieldType . ' \' Unsupported Field Type');
        }

        return $queryBuilder;
    }

    public function parseRoute(string $query): array
    {
        $routeName = substr($query, 3);
        return $this->routeSearchService->findAllByNameLike($routeName);
    }

    /**
     * Search based on user's current active route using default search field
     * @param string $query
     * @return array
     * @throws \ReflectionException
     */
    public function createEntityQueryBuilderByGuess(string $query): array
    {
        $r = $this->requestStack->getSession();
        // Set by CustomAbstractController
        $lastRoute = $r->get('_route');
        $entityName = $this->entitySearchService->getEntityNameFromRouteName($lastRoute);
        if (!$this->entitySearchService->isSearchable($entityName)) return [];
        $entitySimpleName = $entityName;
        $entityName = $this->entitySearchService->getEntityClassNamespace($entityName);
        $defaultFieldName = $entityName::getDefaultSearchFieldName();
        $repo = $this->entityManager->getRepository($entityName);
        $maxResult = $this->requestStack->getCurrentRequest()->get('max');
        if (empty($maxResult)) $maxResult = 10;
        $res = $repo->createQueryBuilder($entityName[0])
            ->where($entityName[0] . '.' . $defaultFieldName . ' like :' . $defaultFieldName)
            ->setParameter($defaultFieldName, $this->makeLikeParam($query))
            ->setMaxResults($maxResult)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
        return ["res" => $res, "redirectUrl" => $this->routeSearchService->getEntityIndexRoutePath($entitySimpleName)];
    }

}