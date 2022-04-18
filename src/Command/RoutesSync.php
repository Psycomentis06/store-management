<?php

namespace App\Command;

use App\Entity\Permission;
use App\Entity\Route;
use App\Utils\Routes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand('app:routes:sync')]
class RoutesSync extends Command
{
    private RouterInterface $router;
    private ManagerRegistry $managerRegistry;

    public function __construct(RouterInterface $router, ManagerRegistry $managerRegistry)
    {
        $this->router = $router;
        $this->managerRegistry = $managerRegistry;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('
        Synchronize application routes to database stored routes
        ');
        $this->setHelp('Sync app routes with DB stored routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->managerRegistry->getManager();
        //Step 1 Insert new routes & Update existing ones
        $routes = $this->router->getRouteCollection()->all();
        // Remove profiler path and any other path not defined in App/Controller dir
        $routes = Routes::cleanRouteCollection($routes);
        $output->writeln("<comment>Step 1:</comment> <info>Starting Insert & Update operations </info>");
        foreach ($routes as $routeName => $routeObject) {
            $dbRoute = $entityManager->getRepository(Route::class)->findOneBy(['name' => $routeName]);
            if (empty($dbRoute)) {
                $route = new Route();
                $route
                    ->setName($routeName)
                    ->setSystem(empty($routeObject->getOption('system')) == false ? $routeObject->getOption('system') : false)
                    ->setDescription(empty($routeObject->getDefault('description')) == false ? $routeObject->getDefault('description') : "")
                    ->setPermission($entityManager->getRepository(Permission::class)->findOneBy(['permission' => Routes::getPermissionName($routeObject)]));
                $entityManager->persist($route);
            } else {
                $dbRoute
                    ->setSystem(empty($routeObject->getOption('system')) == false ? $routeObject->getOption('system') : false)
                    ->setDescription(empty($routeObject->getDefault('description')) == false ? $routeObject->getDefault('description') : "")
                    ->setPermission($entityManager->getRepository(Permission::class)->findOneBy(['permission' => Routes::getPermissionName($routeObject)]));
            }
        }
        // Step 2 Delete removed ones
        $output->writeln("<comment>Step 2: </comment> <info> Cleaning routes </info>");
        $dbRoutes = $entityManager->getRepository(Route::class)->findAll();
        $routes = $this->router->getRouteCollection()->all();
        $routes = Routes::cleanRouteCollection($routes);
        foreach ($dbRoutes as $route) {
            if (empty($routes[$route->getName()])) {
                $entityManager->getRepository(Route::class)->remove($route);
            }
        }
        $entityManager->flush();
        $output->writeln("<info> Finished: Routes are synchronized with database </info>");
        return Command::SUCCESS;
    }
}