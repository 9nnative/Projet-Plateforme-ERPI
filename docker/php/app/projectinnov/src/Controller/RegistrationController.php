<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Entity\Profilepics;
use App\Entity\Notification;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $defaultusrpic = $this->getDoctrine()
        ->getRepository(Profilepics::class)
        ->findOneById(1);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe plein
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $date = new \DateTime('now');
            $tz_object = new DateTimeZone('Europe/Paris');
            $date->setTimezone($tz_object);

            $notification = new Notification(); 
            $notification->setContent("Bienvenue sur le site de la plateforme ERPI !");
            $notification->setDate($date);
            $notification->setUsertarget($user);
            $notification->setType("newmember");

            $user->setProfilepics($defaultusrpic);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($notification);
            $entityManager->flush();

            
            return $this->redirectToRoute('app_login');
            $this->addFlash('success', 'Vous êtes désormais inscrit ! Veuillez vous connecter afin d\'accéder à la plateforme. ');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
