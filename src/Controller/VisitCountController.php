<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitCountController extends AbstractController
{
    private VisitRepository $visitRepository;

    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        // dump($minDate);
        // dump($maxDate); 
        
        $visitEntities = $this->visitRepository->findVisitsBetweenDates($minDate, $maxDate);
        // dump($visitEntities);

        return $this->json(['data'=>count($visitEntities)]);

    }
}
