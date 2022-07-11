<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteCountController extends AbstractController
{
    private CommandRepository $venteRepository;
    public function __construct(CommandRepository $venteRepository)
    {
        $this->venteRepository = $venteRepository;
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        // dump($minDate);
        // dump($maxDate); 
        
        $venteEntities = $this->venteRepository->findVenteBetweenDates($minDate, $maxDate);
        
        $totalVente=0;

        foreach($venteEntities as $vente){
            $totalVente +=$vente->getTotalPrice();}

        return $this->json(['data'=>$totalVente]);
    }
}