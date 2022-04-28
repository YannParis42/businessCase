<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValueBasketController extends AbstractController
{
    private CommandRepository $valueBasketRepository;

    public function __construct(CommandRepository $valueBasketRepository)
    {
        $this->valueBasketRepository = $valueBasketRepository;
    }
    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        dump($minDate);
        dump($maxDate); 

        $venteEntities = $this->valueBasketRepository->findVenteBetweenDates($minDate, $maxDate);
        
        $totalVente=0;

        foreach($venteEntities as $vente){
            $totalVente +=$vente->getTotalPrice();}

        $average= $totalVente / count($venteEntities);    

        return $this->json($average);
    }
}
