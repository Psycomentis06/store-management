<?php

namespace App\Command;

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
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
        $routes = $this->router->getRouteCollection()->all();
        $routes = array_filter($routes, function ($route) {
            return str_starts_with($route->getDefault('_controller'), 'App\Controller');
        });
        foreach ($routes as $route) {
            $output->writeln($route->getPath());
        }

        return Command::SUCCESS;
    }
}