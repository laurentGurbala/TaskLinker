<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegisterType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private EmployeRepository $employeRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route("/bienvenue", name: "app_bienvenue")]
    public function bienvenue(): Response 
    {
        return $this->render("auth/bienvenue.html.twig");
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $employe = new Employe();
        $employe->setStatut("CDI")
                ->setDateArrivee(new \DateTime());

        $form = $this->createForm(RegisterType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employe->setPassword($hasher->hashPassword($employe, $employe->getPassword()));

            $this->entityManager->persist($employe);
            $this->entityManager->flush();

            return $this->redirectToRoute("app_projets");
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form,
        ]);
    }
}
