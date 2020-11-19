<?php

namespace App\Controller;


use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

}
