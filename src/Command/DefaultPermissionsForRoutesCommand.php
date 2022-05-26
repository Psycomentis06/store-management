<?php

namespace App\Command;

use App\Service\RoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:default-permissions-for-routes',
    description: 'Add a short description for your command',
)]
class DefaultPermissionsForRoutesCommand extends Command
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
    }

    protected function configure(): void
    {
        $this->setDescription('
        Add all permissions to default roles
        ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->roleService->addDefaultPermissions();
        return Command::SUCCESS;
    }
}
