<?php

namespace App\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Entity\Files;
use App\Entity\State;
use App\Form\TaskType;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Project;
use App\Form\FilesType;
use App\Form\FollowType;
use App\Form\CommentType;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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

        $prjfollowed = $this->getUser()->getFollow();

        $user = $this->getUser();

        $project = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();

        return $this->render('projects/allprojects.html.twig', [
            'project' => $project,
            'prjfollowed' => $prjfollowed,
            'user' => $user,
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
        return $this->render('user/editUser.html.twig', [
            'formUser' => $forme->createView(),
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    /**
     * @Route("/task/{id}", name="task_details")
     */
    public function showTask(Project $project = null, Task $task = null, UserInterface $user, EntityManagerInterface $em, Request $request)
    {
        if(!$task) // si la tâche n'existe pas on route vers sessions et on affiche une erreur
        {
            $this->addFlash('error', 'Cette tâche n\'existe pas.');
            return $this->redirectToRoute('allprojects');
        }


       $users = $task->getUser();



        return $this->render('tasks/detailTask.html.twig', [
            'task' => $task,
            'users' => $users,
            'editMode' => $task->getId() !==null,
        ]);

    }




    /**
     * @Route("/project/{id}", name="project_details")
     * @Route("/updatecomm/{id}", name="comment_update")
     */
    public function showProject(Project $project = null, State $state = null, Comment $comment = null, Task $task = null, User $user = null, Request $request, SluggerInterface $slugger, EntityManagerInterface $manager) 
    {   
        if(!$project) // si le projet n'existe pas on route vers sessions et on affiche une erreur
        {
            $this->addFlash('error', 'Ce projet n\'existe pas.');
            return $this->redirectToRoute('allprojects');
        }

        $allproducts = $this->getDoctrine()
        ->getRepository(Files::class)
        ->findByProject($project);

        $task = $this->getDoctrine()
        ->getRepository(Task::class)
        ->findByProject($project);

        $prjfollowed = $project->getUsers();

        $id = 4;
        $state = $this->getDoctrine()
        ->getRepository(State::class)
        ->find($id);

        $comments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->findByProject($project);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $nbcomments = count($project->getComments());
        $nbfollows = count($project->getUsers());
        $nbtasks = count($project->getTasks());
        $nbdocs = count($project->getFiles());

        if($form->isSubmitted() && $form->isValid())
        {
                // $comment = new Comment();
                $comment->setUser($this->getUser());
                $comment->setProject($project);
                $date = new \DateTime('now');
                $comment->setCreatedAt($date);
                $manager->persist($comment);
                $manager->flush();
                header("Refresh:0");
                $this->addFlash('success', 'Commentaire posté !');

        }

        $taskform = new Task;
        $formt = $this->createForm(TaskType::class, $taskform);
        $formt->handleRequest($request);
       
        if($formt->isSubmitted() && $formt->isValid())
        {

                $taskform->setOwner($this->getUser());
                $taskform->setProject($project);                
                $taskform->setState($state);
                $date = new \DateTime('now');
                $taskform->setCreatedAt($date);
                $manager->persist($taskform);
                $manager->flush();
                header("Refresh:0");
                $this->addFlash('success', 'La tâche a été créée');

        }
        
        $product = new Files();
        $formf = $this->createForm(FilesType::class, $product);
        $formf->handleRequest($request);

        if ($formf->isSubmitted() && $formf->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $formf->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $this->addFlash('success', 'Le fichier a correctement été uploadé');
                    $product->setUniqueName($newFilename);
                    $product->setPath(dirname("/public/uploads/brochures"));
                    $product->setProject($project);   
                    $manager->persist($product);
                    $manager->flush();
                    // $entityManager->persist($product);
                    // $entityManager->flush();
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setBrochureFilename($newFilename);


            }
            // ... persist the $product variable or any other work

            return $this->redirectToRoute('allprojects');
        }



        return $this->render('projects/detailProject.html.twig', [
            'formComment' => $form->createView(),
            'formTask' => $formt->createView(),
            'formFile' => $formf->createView(),
            'task' => $task,
            'allproducts' => $allproducts,
            'project' => $project,
            'comments' => $comments,
            'nbcomments' => $nbcomments,
            'nbfollows' => $nbfollows,
            'nbdocs' => $nbdocs,
            'nbtasks' => $nbtasks,
            'prjfollowed' => $prjfollowed,
            // 'editMode' => $task->getId() !==null
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
                $task->setOwner($this->getUser());
                $date = new \DateTime('now');
                $task->setCreatedAt($date);
                $manager->persist($task);
                $manager->flush();
                return $this->redirectToRoute('allprojects');
                $this->addFlash('success', 'La tâche a été créée');

        }
        return $this->render('tasks/addTask.html.twig', [
            'formTask' => $form->createView(),
            'editMode' => $task->getId() !==null
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
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Project $project = null, Task $task = null)
    {
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
            'prjfollowed' => $prjfollowed,
            'project' => $project,
            'taskself' => $taskself,
            'taskattr' => $taskattr,
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/followProject/{id}", name="followproject")
     */
    public function followProject(Project $project, Request $request, EntityManagerInterface $manager)
    {
        if(!$project)
        {
            $this->addFlash('error', 'Impossible d\'ajouter ce projet à vos favoris : il n\'existe pas.');
            return $this->redirectToRoute('allprojects');
        } 

        $this->getUser()->addFollow($project);

        $manager->persist($project);

        $manager->flush();   
        
        $this->addFlash('success', 'Le projet a bien été ajouté à vos favoris.');

        return $this->redirectToRoute('allprojects');  

    }
    /**
     * @Route("/addUsersToProject/{id}", name="user_follows_project")
     */
    public function addUsersToProject(Project $project = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$project)
        {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('allprojects');
        }

            $form = $this->createForm(FollowType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                foreach($form->getData() as $stagiaire)// pour chaque stagiaire on ajoute le stagiaire à la session
                {
                    $project->addUsers($stagiaire);
                }
                $manager->persist($project);
                $manager->flush(); 

                return $this->redirectToRoute('project_details', ['id' => $project->getId()] );
            }
            return $this->render('projects/detailProject.html.twig', [
                'formFollow' => $form->createView()
            ]);
        // }
        // return $this->redirectToRoute('project_details', ['id' => $project->getId()] );
    }









}


