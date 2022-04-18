<?php

namespace App\Command;

use App\Entity\Permission;
use App\Utils\Routes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand('app:permissions:sync')]
class PermissionsSync extends Command
{
    private ManagerRegistry $managerRegistry;
    private RouterInterface $router;
    public function __construct(ManagerRegistry $managerRegistry, RouterInterface $router)
    {
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Sync available router's Permissions with saved permissions");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->managerRegistry->getManager();
        // Step 1 : Insert & Update permissions
        $output->writeln("<comment>Step 1: </comment> <info> Inserting permissions </info>");
        $routes = Routes::cleanRouteCollection($this->router->getRouteCollection()->all());
        $permissionRepo = $entityManager->getRepository(Permission::class);
        foreach ($routes as $routeName => $routeObj) {
            $permissionName = Routes::getPermissionName($routeObj);
            $dbPermission = $permissionRepo->findOneBy(['permission' => $permissionName]);
            if (empty($dbPermission)) {
                $permission = new Permission();
                $permission->setPermission($permissionName);
                $entityManager->persist($permission);
            }
        }
        // Step 2 : Clean permissions
        $output->writeln("<comment>Step 2: </comment> <info> Cleaning permissions </info>");
        $permissions = $permissionRepo->findAll();
        foreach ($permissions as $permission) {
            if (!Routes::isPermissionPresent($routes, $permission->getPermission())) {
                $permissionRepo->remove($permission);
            }
        }

        $entityManager->flush();
        $output->writeln("<info> Finished: Permissions are synchronized with database </info>");
        return Command::SUCCESS;
    }
}