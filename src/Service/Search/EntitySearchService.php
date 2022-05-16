<?php

namespace App\Service\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpKernel\KernelInterface;

class EntitySearchService
{
    private const ENTITIES_DIR_NAME = "/src/Entity/";
    private KernelInterface $kernel;
    private EntityManagerInterface $entityManager;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws EntityNotFoundException
     */
    function getEntityClassPathByName(string $name): string
    {

        $name .= '.php';
        $entitiesDirPath = $this->getEntitiesDirPath();
        $entityClasses = scandir($entitiesDirPath);
        $entityClasses = array_filter($entityClasses, function ($entityName) use ($name) {
            return $entityName === $name;
        });

        if (count($entityClasses) >= 1) return Path::makeAbsolute($name, $entitiesDirPath);

        throw new EntityNotFoundException("$name not found");
    }

    public function getEntitiesDirPath(): string
    {
        return $this->kernel->getProjectDir() . self::ENTITIES_DIR_NAME;
    }

    /**
     * For an entity to be searchable must implement the EntitySearchable interface
     * @param string|null $name
     * @param \ReflectionClass|null $class
     * @return bool
     * @throws \ReflectionException
     */
    public function isSearchable(string $name = null, \ReflectionClass $class = null): bool
    {
        $interface = 'App\_Interface\SearchableEntityInterface';
        if ($name !== null) {
            $erc = $this->getEntityReflectionClass($name)->getInterfaces();
            return !empty($erc[$interface]);
        }

        if ($class !== null) {
            return !empty($class->getInterfaces()[$interface]);
        }

        return false;
    }

    /**
     * @throws \ReflectionException
     */
    public function getEntityReflectionClass(string $name): \ReflectionClass
    {
        return new \ReflectionClass($this->getEntityClassNamespace($name));
    }

    public function getEntityClassNamespace(string $name): string
    {
        return 'App\Entity\\' . $name;
    }

    public function findEntitiesByName(string $name = "", bool $associativeArray = false): array
    {
        $entitiesDirPath = $this->getEntitiesDirPath();
        $entityClasses = scandir($entitiesDirPath);
        if ($associativeArray) {
            foreach ($entityClasses as $key => $c) {
                $entityClasses[$key] = str_replace('.php', '', $c);
            }
            return array_filter($entityClasses, function ($c) use ($name) {
                return !str_starts_with($c, '.') && preg_match("/(?i)($name)/", $c);
            });
        } else {
            $res = [];
            foreach ($entityClasses as $key => $c) {
                if (!str_starts_with($c, '.') && preg_match("/(?i)($name)/", $c)) {
                    $c = str_replace('.php', '', $c);
                    $res[] = $c;
                }
            }
            return $res;
        }
    }


    public function findEntityByName(string $name): ?string
    {
        $res =$this->findEntitiesByName($name);
        return match (count($res)) {
            1 => $res[0],
            default => null,
        };
    }

    /**
     * @throws \ReflectionException
     */
    public function findSearchableEntityByName(string $name): ?string
    {
        $res =$this->filterSearchable($this->findEntitiesByName($name));
        return match (count($res)) {
            1 => $res[0],
            default => null,
        };
    }

    /**
     * @throws \ReflectionException
     */
    public function filterSearchable(array $entities): array
    {
        $res = [];
        foreach ($entities as $entity) {
            if ($this->isSearchable($entity)) {
                $res[] = $entity;
            }
        }
        return $res;
    }

    public function fieldExists(string $entity, string $name):?bool
    {
        $fields = $this->entityManager->getClassMetadata($entity)->getFieldNames();
        return count(
          array_filter(
              $fields,
              function ($f) use ($name) {
                  return preg_match("/(?i)($name)/", $f);
              }
          )
        ) === 1;
    }

    public function getFieldType(string $entity, string $name): string
    {
        $fields = $this->entityManager->getClassMetadata($entity);
        return $fields->getTypeOfField($name);
    }
}