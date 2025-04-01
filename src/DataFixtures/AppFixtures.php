<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des employés
        $employees = EmployeeFactory::createMany(5);

        // Créer des projets et leur attribuer des membres
        $projects = ProjectFactory::createMany(2, function () use ($employees) {
            return [
                "members" => EmployeeFactory::randomRange(2, 5),
            ];
        });

        // Créer des tâches
        TaskFactory::createMany(12);

        $manager->flush();
    }
}
