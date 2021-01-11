<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    
    /**
     * @Route("/task/{id}", name="task_details")
     * @Route("/updateTask/{id}", name="task_update")
     */
    public function showTask(Project $project = null, Task $task = null, UserInterface $user, EntityManagerInterface $manager, Request $request)
    {
        if(!$task) // si la tâche n'existe pas on route vers sessions et on affiche une erreur
        {
            $this->addFlash('error', 'Cette tâche n\'existe pas.');
            return $this->redirectToRoute('allprojects');
        }

        if ($task->getProgress($task) > 0 && $task->getProgress($task) < 100){
            $task->setState(2);
            $manager->persist($task);
            $manager->flush();
        } 

        $progress = $task->getProgress();



        if ($progress == 100){
            $task->setState(1);
            $manager->persist($task);
            $manager->flush();
            
        } 

       $users = $task->getUser();
       
        return $this->render('tasks/detailTask.html.twig', [
            'task' => $task,
            'users' => $users,
            'project' => $project,
            'editMode' => $task->getId() !==null,
        ]);

    }



    /**
     * @Route("/addTask", name="task_add")
     * @Route("/updateTask/{id}", name="task_update")
     */
    public function addTask(Task $task = null, Request $request, EntityManagerInterface $manager, MailerInterface $mailer)
    {

        if(!$task)
        {
            $task = new Task();
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
                $task->setOwner($this->getUser());
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
                $task->setCreatedAt($date);
                $manager->persist($task);
                $manager->flush();
                $this->addFlash('success', 'La tâche a été modifiée');
                return $this->redirectToRoute('dashboard');
        }
        return $this->render('tasks/addTask.html.twig', [
            'formTask' => $form->createView(),
            'editMode' => $task->getId() !==null
        ]);
        
    }

    /**
     * @Route("/removeTask/{id}", name="task_remove")
     */
    public function removeTask(Task $task = null, EntityManagerInterface $manager)
    {
        if(!$task)
        {
            $this->addFlash('error', 'Cette tâche n\'existe pas');
            return $this->redirectToRoute('dashboard');
        }
       $usersattr = $task->getUser(); //à voir
       $manager->remove($task);
       $manager->flush();
       $this->addFlash('success', 'Tâche supprimée ');
       return $this->redirectToRoute("dashboard");
    }


}
