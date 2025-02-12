<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $client = new Client();
            $client->setFirstName($faker->firstName())
                   ->setLastName($faker->lastName())
                   ->setEmail($faker->unique()->email())
                   ->setPhoneNumber($faker->phoneNumber())
                   ->setAddress($faker->address());
            $manager->persist($client);
        }

        $manager->flush();
    }
}
