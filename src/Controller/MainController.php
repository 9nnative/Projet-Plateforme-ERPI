<?php

namespace App\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }


    /**
     * @Route("/addProject", name="project_add")
     * @Route("/updateProject/{id}", name="project_update")
     */
    public function addProject(Project $project = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$project)
        {
            $project = new Project();
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
                $this->addFlash('success', 'Le projet a été créé');
                $project->setOwner($this->getUser());
                $date = new \DateTime('now');
                $project->setCreatedAt($date);
                $manager->persist($project);
                $manager->flush();
                return $this->redirectToRoute('allprojects');

        }
        return $this->render('projects/addProject.html.twig', [
            'formProject' => $form->createView(),
            'editMode' => $project->getId() !==null
        ]);
        
    }

    /**
     * @Route("/allprojects", name="allprojects")
     */
    public function listProjects()
    {
        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();

        return $this->render('projects/allprojects.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    /**
     * @Route("/userdetails", name="userdetails")
     */
    public function userDetails()
    {
        return $this->render('user/showUser.html.twig');
    }

    /**
     * @Route("/project/{id}", name="project_details")
     */
    public function showProject(Project $project = null) 
    {   
        if(!$project) // si le projet n'existe pas on route vers sessions et on affiche une erreur
        {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('allprojects');
        }
        $task = $this->getDoctrine()
        ->getRepository(Task::class)
        ->findByProject($project);
        
        return $this->render('projects/detailProject.html.twig', [
            'task' => $task,
            'project' => $project,
        ]);
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
                $this->addFlash('success', 'La tâche a été créée');
                $task->setOwner($this->getUser());
                $date = new \DateTime('now');
                $task->setCreatedAt($date);
                $manager->persist($task);
                $manager->flush();
                return $this->redirectToRoute('allprojects');

        }
        return $this->render('tasks/addTask.html.twig', [
            'formTask' => $form->createView(),
            'editMode' => $task->getId() !==null
        ]);
        
    }
}
