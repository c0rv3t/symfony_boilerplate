<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CsvExporter
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string
     */
    public function exportProducts(): string
    {
        $products = $this->entityManager
                         ->getRepository(Product::class)
                         ->findAllSortedByPriceDesc();

        $csvContent = "\xEF\xBB\xBF";
        $csvContent .= "Name;Description;Price\n";

        foreach ($products as $product) {
            $name = $product->getName();
            $description = $product->getDescription();
            $price = number_format($product->getPrice(), 2, ',', ' ') . "€";

            $csvContent .= sprintf('"%s";"%s";"%s"' . "\n", $name, $description, $price);
        }

        return $csvContent;
    }
}
