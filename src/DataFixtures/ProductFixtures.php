<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Product();
        $product1->setName('Permis de conduire')
                 ->setPrice(4000)
                 ->setDescription('Ceci est un permis de conduire');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Assurance auto')
                 ->setPrice(500)
                 ->setDescription('Ceci est une assurance auto');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Vignette crit\'air 1')
                 ->setPrice(50)
                 ->setDescription('Ceci est une vignette crit\'air 1');
        $manager->persist($product3);

        $manager->flush();
    }
}
