<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/project")]
final class ProjectController extends AbstractController
{
    #[Route('', name: 'app_project_new', methods: ["GET", "POST"])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée un projet vide
        $project = new Project();

        // Crée le formulaire
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        // Vérifie si le formulaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre le nouveau projet en BDD
            $entityManager->persist($project);
            $entityManager->flush();

            // Redirige l'utilisateur vers la liste des projets
            return $this->redirectToRoute("app_project_show", [
                "id" => $project->getId()
            ]);
        }

        // Affiche le formulaire de création d'un projet
        return $this->render('project/new.html.twig', [
            "form" => $form,
        ]);
    }

    #[ROUTE("/{id}", name: "app_project_show", methods: ["GET"])]
    public function show(?Project $project): Response {
        // Si aucun projet n'a été trouvé...
        if ($project == null) {
            // Redirige vers la page principale
            return $this->redirectToRoute("app_main");
        }

        // Affiche le détail d'un projet
        return $this->render("project/show.html.twig", [
            "project" => $project
        ]);
    }
}
