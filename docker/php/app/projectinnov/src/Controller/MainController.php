<?php

namespace App\Controller;


use DateTimeZone;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Files;
use Twig\Environment;
use App\Entity\Ticket;
use App\Form\TaskType;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Project;
use App\Form\FilesType;
use App\Form\FollowType;
use App\Entity\Actuality;
use App\Form\CommentType;
use App\Form\MessageType;
use App\Form\ProjectType;
use App\Entity\Profilepics;
use App\Form\ActualityType;
use App\Entity\Conversation;
use App\Entity\Notification;
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
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        $actualities = $this->getDoctrine()
        ->getRepository(Actuality::class)
        ->findAll();

        return $this->render('main/index.html.twig', [
        'users' => $users,
        'actualities' => $actualities,
        ]);
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu(): Response
    {
        return $this->render('main/cgu.html.twig');
    }
    /**
     * @Route("/adminpanel", name="adminpanel")
     */
    public function admin(Actuality $actuality = null, Ticket $tickets = null, Request $request): Response
    {
        
        $tickets = $this->getDoctrine()
        ->getRepository(Ticket::class)
        ->findAll();

        $actuality = new Actuality;

        $form = $this->createForm(ActualityType::class, $actuality);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $tz_object = new DateTimeZone('Europe/Paris');
            $date = new \DateTime('now');
            $date->setTimezone($tz_object);
            $actuality->setDate($date);  

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actuality);
            $entityManager->flush();
            $this->addFlash('success', 'Actualité créée');

        }

        return $this->render('main/admin.html.twig', [
            'tickets' => $tickets,
            'formActuality' => $form->createView(),
            ]);
    }
    /**
     * @Route("/profile/{id}", name="user_profile")
     */
    public function profile(User $user = null)
    {
        if(!$user) // si le projet n'existe pas on route vers sessions et on affiche une erreur
        {
            $this->addFlash('error', 'Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('main');
        }
        $friends = $user->getFriend();
        
        return $this->render('main/profile.html.twig', [
            'user' => $user,
            'friends'=> $friends,
            ]);
    }
    /**
     * @Route("/notifications", name="notifications")
     */
    public function Notifications()
    {
        $notifs = $this->getUser()->getNotifications();

        return $this->render('user/notifications.html.twig', [
        'notifs' => $notifs,
        ]);
}
    /**
     * @Route("/removeNotification/{id}", name="notification_remove")
     */
    public function RemoveNotification(Notification $notification = null, EntityManagerInterface $manager)
    {
        $notifs = $this->getUser()->getNotifications();
        if(!$notification)
        {
            $this->addFlash('error', 'Cette notification n\'existe pas');
            return $this->redirectToRoute('main');
        }
        $this->addFlash('success', 'Notification supprimée');
       $manager->remove($notification);
       $manager->flush();

        return $this->render('user/notifications.html.twig', [
            'notifs' => $notifs,
        ]);
}

    /**
     * @Route("/addfriend/{id}", name="addfriend")
     */
    public function addFriend(User $user = null, EntityManagerInterface $manager)
    {
        if(!$user) 
        {
            $this->addFlash('error', 'Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('main');
        }
        
        // vérif à faire si l'utilisateur l'a déjà en ami

        $friends = $user->getFriend();
        $user->addUser($this->getUser());
        $currentuser = $this->getUser();
        $currentuser->addUser($user);

        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été ajouté à vos amis !');

        return $this->render('main/profile.html.twig', [
            'friends' => $friends,
            'user' => $user,
            ]);
    }

    /**
     * @Route("/removefriend/{id}", name="removefriend")
     */
    public function removeFriend(User $user = null, EntityManagerInterface $manager)
    {
        if(!$user) 
        {
            $this->addFlash('error', 'Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('main');
        }

        $currentuser = $this->getUser();
        $user->removeUser($this->getUser());
        $manager->persist($user);
        $manager->flush();


        $this->addFlash('success', 'L\'utilisateur a bien été supprimé de vos amis !');

        return $this->redirectToRoute('user_profile', ['id' => $currentuser->getId()]);

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

        return $this->render('main/dashboardv2.html.twig', [
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


