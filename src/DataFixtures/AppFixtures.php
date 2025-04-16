<?php

namespace App\DataFixtures;

use App\Factory\EmployeFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des employés
        $employes = EmployeFactory::createMany(5);

        // Créer des projets et leur attribuer des membres
        $projects = ProjectFactory::createMany(2, function () use ($employes) {
            return [
                "members" => EmployeFactory::randomRange(2, 5),
            ];
        });

        // Créer des tâches
        TaskFactory::createMany(12);

        $manager->flush();
    }
}
