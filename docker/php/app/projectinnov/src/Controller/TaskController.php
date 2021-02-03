<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

       $userstask = $task->getUser();
       $users = $this->getDoctrine()
       ->getRepository(User::class)
       ->findAll();
       
        return $this->render('tasks/detailTask.html.twig', [
            'task' => $task,
            'userstask' => $userstask,
            'users' => $users,
            'project' => $project,
            'editMode' => $task->getId() !==null,
        ]);

    }

    /**
     * @Route("/addUser/{id}/toTask/{task_id}", name="add_user_to_task")
     * @ParamConverter("user", options={"id" = "id"})
     * @ParamConverter("task", options={"id" = "task_id"})
     */
    public function addUserToTask(User $user = null, Task $task = null, Request $request, EntityManagerInterface $manager)
    {

        if(!$task)
        {
            $this->addFlash('error', 'Ce projet n\'existe pas');
        }
        if(!$user)
        {
            $this->addFlash('error', 'Cette personne n\'existe pas : impossible de l\'ajouter au projet.');
        }

        $addusrtotask = $task->addUser($user);
        $manager->persist($addusrtotask);
        $manager->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été ajouté à la tâche.');

        return $this->redirectToRoute('task_details', ['id' => $task->getId()]);
    }    
    /**
     * @Route("/addTask", name="task_add")
     * @Route("/updateTask/{id}", name="task_update")
     */
    public function addTask(Task $task = null, Request $request, EntityManagerInterface $manager)
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
