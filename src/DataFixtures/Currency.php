<?php

namespace App\DataFixtures;

use App\Factory\CurrencyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Currency extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        CurrencyFactory::createMany(10);
        //$manager->flush();
    }
}
