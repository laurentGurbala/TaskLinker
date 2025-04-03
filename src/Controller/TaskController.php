<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/project/{id}/task")]
final class TaskController extends AbstractController
{
    #[Route('/new', name: 'app_task_new', methods: ["GET", "POST"])]
    #[Route('/{taskId}/edit', name: 'app_task_edit', methods: ["GET", "POST"], requirements: ["id" => "\d+", "taskId" => "\d+"])]
    public function new(Project $project, Request $request, EntityManagerInterface $entityManager, 
        TaskRepository $taskRepository, ?int $taskId = null): Response
    {
        // Récupère la tâche
        $task = $this->getProjectAndTask($project, $taskRepository, $taskId);

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
            "task" => $task,
            "isEdit" => $task->getId() !== null,
        ]);
    }

    #[Route("/{taskId}/remove", name: "app_task_remove", methods: ["GET"], requirements: ["id" => "\d+", "taskId" => "\d+"])]
    public function remove(?Project $project, EntityManagerInterface $entityManager, TaskRepository $taskRepository, ?int $taskId = null): Response {
        // Récupère la tâche
        $task = $this->getProjectAndTask($project, $taskRepository, $taskId);

        // Supprime la tâche
        $entityManager->remove($task);
        $entityManager->flush();

        // Redirige vers le projet
        return $this->redirectToRoute("app_project_show", ["id" => $project->getId()]);
    }

    private function getProjectAndTask(?Project $project, TaskRepository $taskRepository, ?int $taskId=null): ?Task
    {
        // Si le projet n'est pas trouvé...
        if ($project == null) {
            // Crée une execption
            throw $this->createNotFoundException("Projet introuvable.");
        }

        // Retourne la tâche
        return $taskId ? $taskRepository->find($taskId) : new Task();
    }
}
