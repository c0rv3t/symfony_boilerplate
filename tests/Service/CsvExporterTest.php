<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CsvExporter;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
    public function testExportProducts(): void
    {
        $product1 = $this->createConfiguredMock(Product::class, [
            'getName' => 'Test product',
            'getDescription' => 'A great product',
            'getPrice' => 666.0,
        ]);
        $product2 = $this->createConfiguredMock(Product::class, [
            'getName' => 'Product 2',
            'getDescription' => 'Another one',
            'getPrice' => 99.99,
        ]);

        $repositoryStub = $this->createMock(ProductRepository::class);
        $repositoryStub->method('findAllSortedByPriceDesc')
                       ->willReturn([$product2, $product1]);

        $entityManagerStub = $this->createMock(EntityManagerInterface::class);
        $entityManagerStub->method('getRepository')
                          ->with(...[$this->equalTo(Product::class)])
                          ->willReturn($repositoryStub);
        
        $csvExporter = new CsvExporter($entityManagerStub);
        $csv = $csvExporter->exportProducts();

        $this->assertStringContainsString("Name;Description;Price", $csv);
        $this->assertStringContainsString('"Test product";"A great product";"666,00€"', $csv);
        $this->assertStringContainsString('"Product 2";"Another one";"99,99€"', $csv);
    }
}