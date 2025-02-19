<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $product = new Product();
        $product->setName('Test');
        $product->setDescription('We are venom');
        $product->setPrice(123.45);

        $this->assertEquals('Test', $product->getName());
        $this->assertEquals('We are venom', $product->getDescription());
        $this->assertEquals(123.45, $product->getPrice());
    }
}