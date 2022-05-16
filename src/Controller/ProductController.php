<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $product,
        ]);
    }
}

