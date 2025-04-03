<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
    #[Route('/team', name: 'app_employee_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        // Récupérer les employés
        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            "employees" => $employees,
        ]);
    }

    #[Route("/team/{id}/edit", name: "app_employee_edit", methods: ["GET", "POST"], requirements: ["id" => "\d+"])]
    public function edit(?Employee $employee, Request $request, EntityManagerInterface $entityManager): Response {
        // Si l'employee n'existe pas...
        if (!$employee) {
            // Rediriger vers la page d'équipe
            return $this->redirectToRoute("app_employee_index");
        }

        // Crée le formulaire
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        // Vérifie si le formulaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre le nouveau projet en BDD
            $entityManager->persist($employee);
            $entityManager->flush();

            // Redirige vers la liste des employées
            return $this->redirectToRoute("app_employee_index");
        }

        // Affiche la page d'édition d'un employé
        return $this->render("employee/new.html.twig", [
            "form" => $form,
            "employee" => $employee
        ]);
    }

    #[Route("/team/{id}/remove", name: "app_employee_remove", methods: ["GET"], requirements: ["id" => "\d+"])]
    public function remove(?Employee $employee, EntityManagerInterface $entityManager) : Response {
        // Si l'employé existe...
        if ($employee != null) {
            // Dissocier l'employé des tâches
            foreach($employee->getTasks() as $task) {
                $task->setMember(null);
                $entityManager->persist($task);
            }

            // Dissocier l'employé des projets
            foreach ($employee->getProjects() as $project) {
                $project->removeMember($employee);
                $entityManager->persist($project);
            }

            // Supprimer l'employé
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        // Redirige vers la page des employés
        return $this->redirectToRoute("app_employee_index");
    }
}
