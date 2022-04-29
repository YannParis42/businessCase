<?php

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCountController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $min_date_string = $request->query->get('min_date');
        $max_date_string = $request->query->get('max_date');

        $minDate =new DateTime( $min_date_string);
        $maxDate =new DateTime( $max_date_string);

        dump($minDate);
        dump($maxDate); 
        
        $userEntities = $this->userRepository->findUserBetweenDates($minDate, $maxDate);
        dump($userEntities);

        return $this->json(['data'=>count($userEntities)]);
    }
}
