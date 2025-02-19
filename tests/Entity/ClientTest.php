<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientProperties(): void
    {
        $client = new Client();
        $client->setFirstName('Ana');
        $client->setLastName('Amari');
        $client->setEmail('ana.amari@deadgame.com');
        $client->setPhoneNumber('0601010101');
        $client->setAddress('12 wakanda street');

        $this->assertEquals('Ana', $client->getFirstName());
        $this->assertEquals('Amari', $client->getLastName());
        $this->assertEquals('ana.amari@deadgame.com', $client->getEmail());
        $this->assertEquals('0601010101', $client->getPhoneNumber());
        $this->assertEquals('12 wakanda street', $client->getAddress());
        $this->assertInstanceOf(\DateTimeInterface::class, $client->getCreatedAt());
    }
}