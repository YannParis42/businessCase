<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbandonCountController extends AbstractController
{
    private CommandRepository $commandRepository;

    public function __construct(CommandRepository $commandRepository)
    {
        $this->commandRepository = $commandRepository;        
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        dump($minDate);
        dump($maxDate);

        $conversionEntities = $this->commandRepository->findConversionBetweenDates($minDate, $maxDate);
        $successEntities = $this->commandRepository->findVenteBetweenDates($minDate, $maxDate);
        $percentage = (count($successEntities) *100) / count($conversionEntities);
        $result = 100 - $percentage;
        

        return $this->json(['data'=>$result]);
    }
}
