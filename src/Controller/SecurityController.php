<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\ChangePasswordType;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private ResetPasswordRepository $resetPasswordRepository, private EntityManagerInterface $em)
    {
        
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/resetPassword', name: 'app_reset')]
    public function reset(Request $request, MailerInterface $mailer):Response
    {
        // 
        $resetPasswordForm = $this->createForm(ResetPasswordType::class);
        $resetPasswordForm->handleRequest($request);
        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $mail = $resetPasswordForm->getData();
            $user = $this->userRepository->findOneBy($mail);
            if($user!=null){
                $resetPassword = new ResetPassword();
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTime());
                $resetPassword->setIsReset(true);
                $resetPassword->setUser($user);
                // dd($resetPassword);
                $this->em->persist($resetPassword);
                $this->em->flush();


                $url = $this->generateUrl('app_change',['token'=>$resetPassword->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

                // $url = '127.0.0.1:8000/changePassword/'.$resetPassword->getToken();
                $email = new Email();
                $email->from('Lanimesalerie42@gmail.com')
                      ->to($user->getEmail())
                      ->subject('Changement de mot de passe')
                      ->html('<p><a href="'.$url.'">Clique ici pour changer le mot de passe</a></p>');
                $mailer->send($email);

                return $this->redirectToRoute('app_home');
            }
           
        }

        return $this->render('security/resetPassword.html.twig', [
            'form' => $resetPasswordForm->createView()
        ]);
    }

    #[Route(path: '/changePassword/{token}', name: 'app_change')]
    public function changePassword(Request $request, $token, UserPasswordHasherInterface $userPasswordHasher)
    {
        $resetPasswordEntity= $this->resetPasswordRepository->findOneBy(['token'=>$token]);
        if($resetPasswordEntity!=null){
            if($resetPasswordEntity->getIsReset()===true){
                $changePasswordForm = $this->createForm(ChangePasswordType::class);
                $changePasswordForm->handleRequest($request);
                
                if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
                        
                    $resetPasswordEntity->setIsReset(false);
                    

                    $user = $resetPasswordEntity->getUser();
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                                $user,
                                $changePasswordForm->get('plainPassword')->getData()
                            )
                        );
                    $this->em->flush();
                    return $this->redirectToRoute('app_home');
                    
                }

                return $this->render('security/changePassword.html.twig', [
                    'form' => $changePasswordForm->createView()
                ]);
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

}