<?php

namespace App\Command;

use App\Entity\Role;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:setup')]
class Setup extends Command
{
    private array $DATA;

    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->DATA = [];
        $this->prepareData();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('
        Insert default required data to database for a fully functional application without
        Inserting them directly to database.
        ');
        $this->setHelp('Setup project with initial configurations like inserting primitive user roles to database');
        $this->addOption('update', '-u', InputArgument::OPTIONAL, 'Updates existing objects', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = new ConsoleLogger($output);
        $entityManager = $this->managerRegistry->getManager();
        $addedCounter = 0;
        $updatedCounter = 0;
        foreach ($this->DATA  as $item) {
            if ($item instanceof Role) {
                // Handle Role entities
                $role = $this->managerRegistry->getRepository(Role::class)->findOneBy(['role' => $item->getRole()]);
                if ($role) {
                    if ($input->getOption('update')) {
                        $oldRoleName = $role->getRole();
                        $role->setRole($item->getRole());
                        $role->setSystem($item->getSystem());
                        $updatedCounter++;
                        $logger->info('Updating role \'' . $oldRoleName . '\'');
                    } else {
                        $logger->warning('Role \'' . $role->getRole() . '\' already exist, if you want to update with new data add -u flag');
                    }
                } else {
                    $entityManager->persist($item);
                    $addedCounter++;
                    $logger->info('Inserting new Role \'' . $item->getRole() . '\'');
                }
            }
        }
        $entityManager->flush();
        $output->writeln('<info>**************************************************</info>');
        $output->writeln('<info>Completed Successfully</info>');
        $output->writeln('<info>Added items: ' . $addedCounter . '</info>');
        $output->writeln('<info>Updated items: ' . $updatedCounter . '</info>');
        $output->writeln('<comment>Execute command with --verbose for more details</comment>');
        return Command::SUCCESS;
    }

    private function prepareData(): void
    {
        // Roles
        $userRole = new Role();
        $userRole->setRole('a');
        $userRole->setSystem(true);
        $this->DATA[] = $userRole;

        $superAdminRole = new Role();
        $superAdminRole->setSystem(true);
        $superAdminRole->setRole('aa');
        $this->DATA[] = $superAdminRole;

        // Permissions

    }

}