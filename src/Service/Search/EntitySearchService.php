<?php

namespace App\Service\Search;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpKernel\KernelInterface;

class EntitySearchService
{
    private const ENTITIES_DIR_NAME = "/src/Entity/";
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel, Filesystem $filesystem)
    {
        $this->kernel = $kernel;
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
        $interface = 'EntitySearchable';
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
}