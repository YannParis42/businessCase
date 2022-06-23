<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{
    #[Route('/space', name: 'app_space')]
    public function index(): Response
    {
        return $this->render('space/index.html.twig', [
            'controller_name' => 'SpaceController',
        ]);
    }
}
