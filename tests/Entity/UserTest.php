<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends TestCase
{
    public function testUserCreationAndPasswordHashing(): void
    {
        /** @var UserPasswordHasherInterface $passwordHasherMock */
        $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setEmail('luna.snow@rivals.com');
        $user->setFirstName('Luna');
        $user->setLastName('Snow');
        $user->setPassword($passwordHasherMock->hashPassword($user, 'password'));

        $this->assertEquals('luna.snow@rivals.com', $user->getEmail());
        $this->assertEquals('Luna', $user->getFirstName());
        $this->assertEquals('Snow', $user->getLastName());
        $this->assertEquals('', $user->getPassword());
    }
}