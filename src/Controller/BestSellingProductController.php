<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BestSellingProductController extends AbstractController
{
    #[Route('/best/selling/product', name: 'app_best_selling_product')]
    public function index(): Response
    {
        return $this->render('best_selling_product/index.html.twig', [
            'controller_name' => 'BestSellingProductController',
        ]);
    }
}
