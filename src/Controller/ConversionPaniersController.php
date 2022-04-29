<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionPaniersController extends AbstractController
{
    private CommandRepository $convPanierRepository;
    private VisitRepository $visitRepository;

    public function __construct(CommandRepository $convPanierRepository, VisitRepository $visitRepository)
    {
        $this->convPanierRepository = $convPanierRepository;
        $this->visitRepository= $visitRepository;
    }

  


    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        dump($minDate);
        dump($maxDate); 

        $visits = $this->visitRepository->findVisitsBetweenDates($minDate, $maxDate);
        $paniersNumber = $this->convPanierRepository->findCommandBetweenDates($minDate, $maxDate);

        $result= (count($paniersNumber)*100)/count($visits);
        return $this->json(['data'=>$result]);
    }
}
