<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }



    #[Route("/product/create", name: "product_create", methods: ["GET"])]
    public function create(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName("Keyboard_num_" . rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response("Saved new product with id " . $product->getId());
    }



    #[Route("/product/show", name: "product_show_all", methods: ["GET"])]
    public function showAll(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }



    #[Route("product/show/{id}", name: "product_by_id", methods: ["GET"])]
    public function showById(
        ProductRepository $productRepository,
        int $id
    ): Response
    {
        $product = $productRepository->find($id);

        return $this->json($product);
    }



    #[Route("product/delete/{id}", name: "product_delete_by_id", methods: ["GET"])]
    public function deleteById(
        ManagerRegistry $doctrine,
        int $id
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException("No product found with id $id");
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("product_show_all");
    }



    #[Route("/product/update/{id}/{value}", name: "product_update", methods: ["GET"])]
    public function update(
        ManagerRegistry $doctrine,
        int $id,
        int $value
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException("No product found with id $id");
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute("product_show_all");
    }



    #[Route('/product/view', name: 'product_view_all')]
    public function viewAll(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $data = [
            "siteTitle" => "MVC",
            "pageTitle" => "Produkter",
            "products" => $products
        ];

        return $this->render('product/view.html.twig', $data);
    }



    #[Route('/product/view/{value}', name: 'product_view_min_value')]
    public function viewMinVal(
        ProductRepository $productRepository,
        int $value
    ): Response
    {
        $products = $productRepository->findByMinVal($value);

        $data = [
            "siteTitle" => "MVC",
            "pageTitle" => "Produkter",
            "products" => $products
        ];

        return $this->render('product/view.html.twig', $data);
    }



    #[Route('/product/view2/{value}', name: 'product_view2_min_value')]
    public function view2MinVal(
        ProductRepository $productRepository,
        int $value
    ): Response
    {
        $products = $productRepository->findByMinVal2($value);

        $data = [
            "siteTitle" => "MVC",
            "pageTitle" => "Produkter",
            "products" => $products
        ];

        return $this->render('product/view2.html.twig', $data);
    }
}
