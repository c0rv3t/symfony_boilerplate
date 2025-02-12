<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Security\Voter\ProductVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllSortedByPriceDesc();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/create', name: 'product_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::ADD, Product::class)) {
            $this->addFlash('error', 'You do not have permission to create a product.');
            return $this->redirectToRoute('product_index');
        }

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'The product was successfully added.');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
            'is_editing' => false,
        ]);
    }

    #[Route('/products/edit/{id}', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::EDIT, $product)) {
            $this->addFlash('error', 'You do not have permission to edit a product.');
            return $this->redirectToRoute('product_index');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'The product was successfully edited.');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
            'is_editing' => true,
        ]);
    }

    #[Route('/products/delete/{id}', name: 'product_delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::DELETE, $product)) {
            $this->addFlash('error', 'You do not have permission to delete a product.');
            return $this->redirectToRoute('product_index');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'The product was successfully deleted.');

        return $this->redirectToRoute('product_index');
    }

    #[Route('/products/export', name: 'product_export')]
    public function export(\App\Service\CsvExporter $csvExporter): Response
    {
        $csvContent = $csvExporter->exportProducts();

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');

        return $response;
    }
}
