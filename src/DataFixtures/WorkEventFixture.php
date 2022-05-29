<?php

namespace App\DataFixtures;

use App\Factory\WorkEventFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WorkEventFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        WorkEventFactory::createMany(20);
    }
}
