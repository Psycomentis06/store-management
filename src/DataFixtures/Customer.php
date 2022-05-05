<?php

namespace App\DataFixtures;

use App\Factory\CustomerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Customer extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        CustomerFactory::createMany(20);
        //$manager->flush();
    }
}
