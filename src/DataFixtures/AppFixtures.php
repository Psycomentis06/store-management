<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    public function load(ObjectManager $manager): void
    {
        // Execute custom commands first
        $roleRepo = $manager->getRepository(Role::class);
        $userRole = $roleRepo->findOneBy(['role' => 'ROLE_USER']);
        $superAdminRole = $roleRepo->findOneBy(['role' => 'ROLE_SUPERUSER']);

        $user = (new User())
            ->setUsername("ali_amor")
            ->setEmail('ali@a.com')
            ->setPassword('123456789');
        if (!empty($userRole))
            $user->addRole($userRole);
        else {
            $userRole = (new Role())
                ->setRole('user');
            $manager->persist($userRole);
            $user->addRole($userRole);
        }
        if (!empty($superAdminRole))
            $user->addRole($superAdminRole);
        else {
            $userRole = (new Role())
                ->setRole('superadmin');
            $manager->persist($userRole);
            $user->addRole($userRole);
        }
        $this->userService->create($user);
    }
}
