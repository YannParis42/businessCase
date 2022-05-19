<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    public function __construct(private BasketService $basketservice)
{
    
}
    #[Route('/basket', name: 'app_basket')]
    public function basket(): Response
    {
        $panier = $this->basketservice->getBasket($this->getUser());
        
        return $this->render('basket/index.html.twig', [
            'panier' => $panier
        ]);
    }
 

    #[Route('/basket/add/{id}', name: 'app_add_basket')]
    public function addBasket($id, ProductRepository $productRepository)
    {   
        $product = $productRepository->find($id);
        $this->basketservice->addProduct($this->getUser(),  $product);

        return $this->redirectToRoute('app_basket');
      
    }
    
    #[Route('/basket/remove/{id}', name: 'app_remove_basket')]
    public function removeBasket($id, ProductRepository $productRepository)
    {   
        $product = $productRepository->find($id);
        $this->basketservice->removeProduct($this->getUser(),  $product);

        return $this->redirectToRoute('app_basket');
      
    }
}
