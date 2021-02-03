<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use HTMLPurifier;
use App\Entity\Task;
use App\Entity\Files;
use App\Form\TaskType;
use App\Entity\Comment;
use App\Entity\Project;
use App\Form\FilesType;
use App\Form\FollowType;
use App\Form\CommentType;
use App\Form\ProjectType;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProjectController extends AbstractController
{
    /**
     * @Route("/allprojects", name="allprojects")
     */
    public function listProjects()
    {

        $prjfollowed = $this->getUser()->getFollow();

        $user = $this->getUser();

        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();

        

        return $this->render('projects/allprojects.html.twig', [
            'projects' => $projects,
            'prjfollowed' => $prjfollowed,
            'user' => $user,
        ]);
    }
    
    /**
     * @Route("/project/{id}", name="project_details")
     */
    public function showProject(Project $project = null, 
    // Notification $notification, 
    Comment $comment = null, 
    Task $task = null, 
    Request $request, 
    SluggerInterface $slugger, 
    EntityManagerInterface $manager) 
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

        $statetotal = 0;

        foreach($task as $solotask)
        {
            if ($solotask->getProgress($task) > 0 && $solotask->getProgress($task) < 100){
                $solotask->setState(2);
                $manager->persist($solotask);
                $manager->flush();
            } 

            $intprogresstask = $solotask->getProgress($task);
            $statetotal = $intprogresstask + $statetotal;

        }

        if (count($project->getTasks()) > 0){

        $statetotaldone = $statetotal / count($project->getTasks());

        $project->setProgress($statetotaldone);

        // $totaltaskscost = $project->getTasks()->getFinancialDetails();

        // $budgetaftertasks = $project->getFincancialDetails() - $totaltaskscost;

        if ($statetotaldone == 100){
            $project->setState(1);
            $manager->persist($project);
            $manager->flush();
        } 


        }
        

        $prjfollowed = $project->getUsers();

        $id = 4;

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
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
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
                $taskform->setState(4);
                $date = new \DateTime('now');
                $taskform->setCreatedAt($date);
                $manager->persist($taskform);
                $manager->flush();
                header("Refresh:0");
                $this->addFlash('success', 'La tâche a été créée');

        }

        $formu = $this->createForm(FollowType::class);
        $formu->handleRequest($request);
       
        if($formu->isSubmitted() && $formu->isValid())
        {   
            //  
     

                foreach($formu->getData() as $user)// pour chaque user on ajoute le user au projet
                {
                  $project->addUser($user);
                  $date = new \DateTime('now');

                  $tz_object = new DateTimeZone('Europe/Paris');
                  $date->setTimezone($tz_object);
                  
                  
                  $projectname = $project->getTitle();
                  $notification = new Notification();
                  $sender = $this->getUser()->getForename();

                  
                  $notification->setContent("$sender vous a ajouté au projet $projectname !");
                  $notification->setDate($date);
                  $notification->setUsertarget($user);
                  $notification->setType("newprj");

                  $manager->persist($notification);
                }
                $manager->persist($project);
                
                $manager->flush(); 
                
                $this->addFlash('success', 'La personne a correctement été ajoutée au projet. ');

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
            'formAddUsers' => $formu->createView(),
            'task' => $task,
            'allproducts' => $allproducts,
            'project' => $project,
            'comments' => $comments,
            'nbcomments' => $nbcomments,
            'nbfollows' => $nbfollows,
            'nbdocs' => $nbdocs,
            'nbtasks' => $nbtasks,
            'prjfollowed' => $prjfollowed,
            // 'totaltaskscost' => $totaltaskscost
            // 'editMode' => $task->getId() !==null
        ]);
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
            if($project->getDateStart() < $project->getDateEnd())
            {
                $this->addFlash('success', 'Le projet a été créé');
                $project->setOwner($this->getUser());
                $project->setState(4);
                $tz_object = new DateTimeZone('Europe/Paris');
                $date = new \DateTime('now');
                $date->setTimezone($tz_object);
                $project->setCreatedAt($date);
                $manager->persist($project);
                $manager->flush();
                return $this->redirectToRoute('allprojects');

            } else $this->addFlash('error', 'La date de fin du projet doit être supérieure à la date de début.');  


        }
        return $this->render('projects/addProject.html.twig', [
            'formProject' => $form->createView(),
            'editMode' => $project->getId() !==null
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
     * @Route("/removeProject/{id}", name="project_remove")
     */
    public function removeProject(Project $project = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$project)
        {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('allprojects');
        }

        $tasks = $project->getTasks();

        foreach($tasks as $task)
        {
             $manager->remove($task);
        }

       $manager->remove($project);
       $manager->flush();

       $this->addFlash('success', 'Le projet a correctement été supprimé.');
       return $this->redirectToRoute("allprojects");
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
                foreach($form->getData() as $usr)
                {
                    $project->addUsers($usr);

    
                }
                $manager->persist($project);
                $manager->persist($notification);
                $manager->flush(); 

                return $this->redirectToRoute('project_details', ['id' => $project->getId()] );
            }
            return $this->render('projects/detailProject.html.twig', [
                'formFollow' => $form->createView()
            ]);
        // }
        // return $this->redirectToRoute('project_details', ['id' => $project->getId()] );
    }

    /**
     * @Route("/removeComment/{id_project}/{id}", name="remove_comment")
     * @ParamConverter("project", options={"id" = "id_project"})
     */
    public function removeComment(Project $project = null, Comment $comment = null, EntityManagerInterface $manager)
    {

        if(!$comment)
        {
            $this->addFlash('error', 'Ce commentaire n\'existe pas');
            return $this->redirectToRoute('allprojects');
        }


       $manager->remove($comment);
       $manager->flush();
       $this->addFlash('success', 'Commentaire supprimé !');
       return $this->redirectToRoute('project_details', ['id' => $project->getId()] );
       
    }


}
