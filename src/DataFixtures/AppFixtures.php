<?php

namespace App\DataFixtures;

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
        $user = (new User())
            ->setUsername("ali_amor")
            ->setEmail('ali@a.com')
            ->setPassword('123456789');
        $this->userService->create($user);
    }
}
