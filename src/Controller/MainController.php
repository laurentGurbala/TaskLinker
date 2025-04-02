<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProjectRepository $repository): Response
    {
        // Récupère les projets
        $projects = $repository->findActiveProjects();

        // Affiche la page d'acceuil avec la liste des projets
        return $this->render('main/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
