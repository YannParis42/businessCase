<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionCountController extends AbstractController
{
    private CommandRepository $conversionCountRepository;

    public function __construct(CommandRepository $conversionCountRepository)
    {
        $this->conversionCountRepository = $conversionCountRepository;
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

   
        
        $conversionEntities = $this->conversionCountRepository->findConversionBetweenDates($minDate, $maxDate);
        $successEntities = $this->conversionCountRepository->findVenteBetweenDates($minDate, $maxDate);
        $percentage = (count($successEntities) *100) / count($conversionEntities);

        return $this->json(['data'=>$percentage]);
    }
}
