<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'client_index')]
    public function index(ClientRepository $clientRepository): Response
    {
        if (!$this->isGranted(ClientVoter::INDEX, Client::class)) {
            $this->addFlash('error', 'You do not have permission to access this page.');
            return $this->redirectToRoute('home');
        }

        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/clients/create', name: 'client_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::ADD, Client::class)) {
            $this->addFlash('error', 'You do not have permission to create a client.');
            return $this->redirectToRoute('client_index');
        }

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'The client was successfully created.');

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/form.html.twig', [
            'form' => $form->createView(),
            'is_editing' => false,
        ]);
    }
    
    #[Route('/clients/edit/{id}', name: 'client_edit')]
    public function edit(Client $client, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::EDIT, $client)) {
            $this->addFlash('error', 'You do not have permission to edit a client.');
            return $this->redirectToRoute('client_index');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'The client was successfully edited.');

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/form.html.twig', [
            'form' => $form->createView(),
            'is_editing' => true,
        ]);
    }

    #[Route('/clients/delete/{id}', name: 'client_delete')]
    public function delete(Client $client, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::DELETE, $client)) {
            $this->addFlash('error', 'You do not have permission to delete a client.');
            return $this->redirectToRoute('client_index');
        }

        $entityManager->remove($client);
        $entityManager->flush();

        $this->addFlash('success', 'The client was successfully deleted.');

        return $this->redirectToRoute('client_index');
    }
}
