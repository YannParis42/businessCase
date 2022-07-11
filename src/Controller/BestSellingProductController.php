<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BestSellingProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        $productEntities = $this->productRepository->getTotalProductSoldDesc($minDate, $maxDate);
     

        return $this->json(['data'=>$productEntities]);

    }
}
