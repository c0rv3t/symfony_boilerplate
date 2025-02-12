<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:add-client',
    description: 'Adds a client with the terminal using a command.',
)]
class AddClientCommand extends Command
{
    private EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question("First name : ");
        $firstName = $helper->ask($input, $output, $question);
        if (empty($firstName) || !preg_match('/^[a-zA-Z\s\'-]+$/', $firstName)) {
            $output->writeln("<error>The first name must contain only letters, spaces, apostrophes and dashes.</error>");
            return Command::FAILURE;
        }

        $question = new Question("Last name : ");
        $lastName = $helper->ask($input, $output, $question);
        if (empty($lastName) || !preg_match('/^[a-zA-Z\s\'-]+$/', $lastName)) {
            $output->writeln("<error>The last name must contain only letters, spaces, apostrophes and dashes.</error>");
            return Command::FAILURE;
        }

        $question = new Question("Email : ");
        $email = $helper->ask($input, $output, $question);
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln("<error>Invalid email address.</error>");
            return Command::FAILURE;
        }
        $existingClient = $this->entityManager
            ->getRepository(Client::class)
            ->findOneBy(['email' => $email]);
        if ($existingClient) {
            $output->writeln("<error>This email is already in use.</error>");
            return Command::FAILURE;
        }

        $question = new Question("Phone number : ");
        $phoneNumber = $helper->ask($input, $output, $question);
        if (empty($phoneNumber)) {
            $output->writeln("<error>Invalid phone number.</error>");
            return Command::FAILURE;
        }

        $question = new Question("Address : ");
        $address = $helper->ask($input, $output, $question);
        if (empty($address)) {
            $output->writeln("<error>Invalid address.</error>");
            return Command::FAILURE;
        }

        $client = new Client();
        $client->setFirstName($firstName)
               ->setLastName($lastName)
               ->setEmail($email)
               ->setPhoneNumber($phoneNumber)
               ->setAddress($address);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln("<info>Client successfully added.</info>");

        return Command::SUCCESS;
    }
}