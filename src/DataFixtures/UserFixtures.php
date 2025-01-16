<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $admin = new User();
        $admin->setEmail('admin@admin.admin')
              ->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('adminpassword');
        $manager->persist($admin);

        $this->addReference('user0', $admin);

        for ($i = 1; $i <= 4; $i++) {
            $userFirstName = $faker->firstName();
            $userLastName = $faker->lastName();

            $user = new User();
            $user->setEmail($userFirstName . '.' . $userLastName . '@example.com')
                 ->setRoles(['ROLE_USER']);
            $user->setPassword('password');
            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
    }
}
