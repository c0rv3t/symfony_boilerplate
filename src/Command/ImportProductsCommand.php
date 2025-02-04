<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Import depuis un fichier CSV.',
)]
class ImportProductsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Chemin du fichier CSV à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            $output->writeln('<error>Fichier non trouvé.</error>');
            return Command::FAILURE;
        }

        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
        } catch (\Exception $e) {
            $output->writeln("<error>Error reading CSV file: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        $records = $csv->getRecords();
        $imported = 0;
        $skipped = 0;
        
        foreach ($records as $record) {
            $record = array_combine(
                array_map('trim', array_keys($record)),
                array_map('trim', $record)
            );
            $record = array_change_key_case($record, CASE_LOWER);
            
            if (!isset($record['name'], $record['description'], $record['price'])) {
                $output->writeln("<error>Missing one of the required columns in record: " . json_encode($record) . "</error>");
                $skipped++;
                continue;
            }
            
            $product = new Product();
            $product->setName($record['name']);
            $product->setDescription($record['description']);

            $price = str_replace(',', '.', $record['price']);
            $product->setPrice((float) $price);
            
            $this->entityManager->persist($product);
            $imported++;
        }
        
        $this->entityManager->flush();
        
        $output->writeln("<info>Imported $imported products. Skipped $skipped rows due to missing columns.</info>");
        return Command::SUCCESS;
    }
}