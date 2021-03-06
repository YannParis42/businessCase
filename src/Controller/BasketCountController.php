<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketCountController extends AbstractController
{
    private CommandRepository $basketRepository;

    public function __construct(CommandRepository $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function __invoke(Request $request)
    {
        dump($this->getUser());

        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

  
        
        $basketEntities = $this->basketRepository->findBasketBetweenDates($minDate, $maxDate);
    

        return $this->json(['data'=>count($basketEntities)]);
    }
}
