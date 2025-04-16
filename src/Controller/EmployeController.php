<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe', methods: ["GET"])]
    public function index(EmployeRepository $employeRepository): Response
    {
        // Récupérer les employés
        $employes = $employeRepository->findAll();

        return $this->render('employe/index.html.twig', [
            "employes" => $employes,
        ]);
    }

    #[Route("/employe/{id}/edit", name: "app_employe_edit", methods: ["GET", "POST"], requirements: ["id" => "\d+"])]
    public function edit(?Employe $employe, Request $request, EntityManagerInterface $em) : Response {
        $this->checkEmployeExist($employe);

        // Créer le formulaire
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        // Vérifier si le formulaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employe);
            $em->flush();

            // Rediriger vers la liste des employés
            return $this->redirectToRoute("app_employe");
        }

        // Afficher la page d'édition d'un employé
        return $this->render("employe/new.html.twig", [
            "form" => $form,
            "employe" => $employe,
        ]);
    }

    #[Route("/team/{id}/remove", name: "app_employe_remove", methods: ["GET"], requirements: ["id" => "\d+"])]
    public function remove(?Employe $employe, EntityManagerInterface $em): Response {
        $this->checkEmployeExist($employe);

        // Dissocier l'employé des tâches
        foreach($employe->getTasks() as $task) {
            $task->setMember(null);
            $em->persist($task);
        }

        // Dissocier l'employé des projets
        foreach ($employe->getProjects() as $project) {
            $project->removeMember($employe);
            $em->persist($project);
        }

        // Supprimer l'employé
        $em->remove($employe);
        $em->flush();

        // Redirige vers la page des employés
        return $this->redirectToRoute("app_employe");
    }

    private function checkEmployeExist(Employe $employe){
        // Si l'employé n'existe pas...
        if (!$employe) {
            // Rediriger vers la page d'équipe
            return $this->redirectToRoute("app_employe");
        }
    }
}
