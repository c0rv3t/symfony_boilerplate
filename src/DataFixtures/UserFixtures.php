<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $admin = new User();
        $admin->setFirstName('Admin')
            ->setLastName('User')
            ->setEmail('admin@admin.admin')
            ->setRoles(['ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_USER']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $manager->persist($admin);

        $this->addReference('user0', $admin);

        for ($i = 1; $i <= 2; $i++) {
            $userFirstName = $faker->firstName();
            $userLastName = $faker->lastName();

            $user = new User();
            $user->setFirstName($userFirstName)
                ->setLastName($userLastName)
                ->setEmail($userFirstName . '.' . $userLastName . '@example.com')
                ->setRoles(['ROLE_MANAGER', 'ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        for ($i = 3; $i <= 10; $i++) {
            $userFirstName = $faker->firstName();
            $userLastName = $faker->lastName();

            $user = new User();
            $user->setFirstName($userFirstName)
                ->setLastName($userLastName)
                ->setEmail($userFirstName . '.' . $userLastName . '@example.com')
                ->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
    }
}
