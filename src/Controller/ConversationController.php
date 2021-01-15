<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Form\ConversationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ConversationController extends AbstractController
{
    /**
     * @Route("/conv/{id}", name="conv_details")
     */
    public function detailConv(Message $message = null, Conversation $conversation, Request $request, EntityManagerInterface $manager){


        $messages = $this->getDoctrine()
        ->getRepository(Message::class)
        ->findByConversation($conversation);

        
        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                
                $message->setSentBy($this->getUser());

                if($this->getUser() == $conversation->getRecipient()){

                    $message->setSentTo($conversation->getOwner());
                }else{
                    $message->setSentTo($conversation->getRecipient());
                }
                $conversation->setLastMessage($message);
                $message->setConversation($conversation);
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
                $message->setSentAt($date);
                $manager->persist($message);
                $manager->flush();

                header("Refresh:0");

                $this->addFlash('success', 'Message posté !');

        }
        
        return $this->render('user/detailmessage.html.twig', [
            'formMessage' => $form->createView(),
            'messages' => $messages,
            'conversation' => $conversation,
            ]);
    }

    /**
     * @Route("/allmessages", name="allmessages")
     */
    public function listMessages(Request $request, EntityManagerInterface $manager)
    {
      
        $messages = $this->getUser()->getMessages();

        $user = $this->getUser();

        $messages = $this->getDoctrine()
        ->getRepository(Message::class)
        ->findAll();

        $conversations = $this->getDoctrine()
        ->getRepository(Conversation::class)
        ->findAll();

        $conversation = new Conversation;
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                
                $conversation->setOwner($this->getUser());
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
                $conversation->setCreatedAt($date);
                $manager->persist($conversation);
                $manager->flush();
                header("Refresh:0");
                $this->addFlash('success', 'Conversation créée ! Vous pouvez désormais envoyer un message en cliquant sur le détail de la conversation.');

        }

        return $this->render('user/allmessages.html.twig', [
            'formAddConv' => $form->createView(),
            'messages' => $messages,
            'conversations' => $conversations,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/removeMessage/{id}", name="remove_message")
     */
    public function removeMessage(Message $message = null, EntityManagerInterface $manager)
    {
        $conversation = $message->getConversation();

        if(!$message)
        {
            $this->addFlash('error', 'Ce message n\'existe pas');
            return $this->redirectToRoute('allmessages');
        }
        
       $message->setContent("<p class ='uk-text-small uk-text-italic'>Message supprimé</p>");
       $manager->persist($message);
       $manager->flush();
       header("Refresh:0");
       $this->addFlash('success', 'Message supprimé !');
       return $this->redirectToRoute('conv_details', ['id' => $conversation->getId()]);
       
    }

}
