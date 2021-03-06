<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecurrenceController extends AbstractController
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

     

        $recurence = $this->commandRepository->findRecurrenceNewBetweenDates($minDate, $maxDate);
        $ventes = $this->commandRepository->findVenteBetweenDates($minDate, $maxDate);
        $result = 0;
        if (count($ventes) > 0) {
        $nbCommandesAncien = count($ventes) - count($recurence);
        $result = ($nbCommandesAncien * 100)/ count($ventes);
        }
        return new JsonResponse(['data'=>$result]);

    }
}
