<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function index(MailerInterface $mailer): Response
    {

        $email = new Email();
        $email->from('Lanimesalerie42@gmail.com')
              ->to('tortuga4281@yahoo.fr')
              ->subject('Changement de mot de passe')
            //   ->text('') pour envoyer juste du texte
              ->html('<p>Bonjour!</p>');
        $mailer->send($email);

        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }
}
