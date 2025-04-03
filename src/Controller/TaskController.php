<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/project/{id}/task/new', name: 'app_task_new', methods: ["GET", "POST"])]
    #[Route('/project/{id}/task/{taskId}/edit', name: 'app_task_edit', methods: ["GET", "POST"])]
    public function new(Project $project, Request $request, EntityManagerInterface $entityManager, 
        TaskRepository $taskRepository, ?int $taskId = null): Response
    {
        // Si le projet n'est pas trouvé...
        if ($project == null) {
            // Redirige vers la page principale
            return $this->redirectToRoute("app_main");
        }

        // Si on est en mode édition, on récupère la tâche
        $task = $taskId ? $taskRepository->find($taskId) : new Task();

        // Assigne le projet (uniquement en mode création)
        if (!$taskId) {
            $task->setProject($project);
        }

        // Crée le formulaire
        $form = $this->createForm(TaskType::class, $task, [
            "project" => $project,
        ]);
        $form->handleRequest($request);

        // Vérifie que le formulaire à été soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre la tâche
            $entityManager->persist($task);
            $entityManager->flush();

            // Redirige vers la page de détail du projet
            return $this->redirectToRoute("app_project_show", [
                "id" => $project->getId(),
            ]);
        }

        // Affiche le formulaire
        return $this->render('task/new.html.twig', [
            'form' => $form,
            "project" => $project,
            "isEdit" => $task->getId() !== null,
        ]);
    }
}
