<?php

namespace App\Controller;


use DateTimeZone;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Files;
use App\Form\TaskType;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Project;
use App\Form\FilesType;
use App\Form\FollowType;
use App\Form\CommentType;
use App\Form\MessageType;
use App\Form\ProjectType;
use App\Entity\Profilepics;
use App\Entity\Conversation;
use Symfony\Component\Mime\Email;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
     * @Route("/edituser", name="edituser")
     */
    public function editUser(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em, Request $request)
    {

        $currentuser = $this->getUser();
        $forme = $this->createForm(UserType::class, $currentuser);
        $forme->handleRequest($request);

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if($forme->isSubmitted() && $forme->isValid())
        {

              
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentuser);
            $entityManager->flush();
            $this->addFlash('success', 'Les modifications ont bien été enregistrées');

        }
        
        $allprofilepics = $this->getDoctrine()
        ->getRepository(Profilepics::class)
        ->findAll();


        return $this->render('user/editUser.html.twig', [
            'formUser' => $forme->createView(),
            'last_username' => $lastUsername, 
            'error' => $error,
            'allprofilepics' => $allprofilepics
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Project $project = null, Task $task = null, Request $request)
    {
        

        $taskform = new Task;
        $formt = $this->createForm(TaskType::class, $taskform);
        $formt->handleRequest($request);
       
        if($formt->isSubmitted() && $formt->isValid())
        {

                $taskform->setOwner($this->getUser());
                $taskform->setProject($project);                
                $taskform->setState(4);
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
                $taskform->setCreatedAt($date);
                $manager->persist($taskform);
                $manager->flush();
                header("Refresh:0");
                $this->addFlash('success', 'La tâche a été mise à jour');

        }

        $prjfollowed = $this->getUser()->getFollow();

        $taskself = $this->getUser()->getTasks();

        $taskattr = $this->getUser()->getAttrtask();

        $project = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();

        $tasks = $this->getDoctrine()
        ->getRepository(Task::class)
        ->findAll();

        return $this->render('main/dashboard.html.twig', [
            'formTask' => $formt->createView(),
            'prjfollowed' => $prjfollowed,
            'project' => $project,
            'taskself' => $taskself,
            'taskattr' => $taskattr,
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/help", name="help")
     */
    public function help()
    {
        return $this->render('main/help.html.twig');
    }
    /**
     * @Route("/versions", name="versions")
     */
    public function versions()
    {
        return $this->render('main/versions.html.twig');
    }

    /**
     * @Route("/userdetails", name="userdetails")
     */
    public function userDetails()
    {
        return $this->render('user/showUser.html.twig');
    }

}


