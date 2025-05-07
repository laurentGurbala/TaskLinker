<?php

namespace App\Security\Voter;

use App\Entity\Employe;
use App\Repository\ProjetRepository;
use App\Repository\TacheRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjetVoter extends Voter
{
    public function __construct(
        private ProjetRepository $projetRepository,
        private TacheRepository $tacheRepository
    ) { }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === "acces_projet" || $attribute === "acces_tache";
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($attribute === "acces_projet") {
            $projet = $this->projetRepository->find($subject);
        } else {
            $tache = $this->tacheRepository->find($subject);
            $projet = $tache?->getProjet();
        }

        /** @var Employe $user */
        $user = $token->getUser();

        if (!$user instanceof Employe || !$projet) {
            return false;
        }

        return $user->isAdmin() || $projet->getEmployes()->contains($user);
    }
}
