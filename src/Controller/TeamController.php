<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        // Récupérer les employés
        $employees = $employeeRepository->findAll();

        return $this->render('team/index.html.twig', [
            "employees" => $employees,
        ]);
    }
}
