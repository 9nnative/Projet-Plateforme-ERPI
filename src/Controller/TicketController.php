<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Ticket;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    /**
     * @Route("/addticket", name="addticket")
     * @Route("/updateTicket/{id}", name="ticket_update")
     */
    public function addTicket(Ticket $ticket = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$ticket)
        {
            $ticket = new Ticket();
        }

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('success', 'Ticket envoyé !');
            $tz_object = new DateTimeZone('Europe/Paris');
            $date = new \DateTime('now');
            $date->setTimezone($tz_object);
            $ticket->setCreatedAt($date);

            $ticket->setSentBy($this->getUser());

            $manager->persist($ticket);

            $manager->flush(); 
           
        }

        return $this->render('main/addticket.html.twig', [
            'formTicket' => $form->createView()
        ]);
    }




}
