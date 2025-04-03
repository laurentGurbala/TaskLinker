<?php

namespace App\Form;

use App\Entity\employee;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['project']) || !$options['project'] instanceof Project) {
            throw new \InvalidArgumentException('Le projet doit être passé au formulaire.');
        }

        // On récupère bien le projet ici
        $project = $options['project']; 

        $builder
            ->add('title', TextType::class, [
                "required" => true,
            ])
            ->add('description', TextareaType::class, [
                "required" => false,
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                "required" => false,
            ])
            ->add('status', EnumType::class, [
                "class" => TaskStatus::class,
                "multiple" => false,
                "required" => true,
            ])
            ->add('member', EntityType::class, [
                'class' => employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getFirstName() . ' ' . $employee->getLastName();
                },
                'query_builder' => function (EntityRepository $er) use ($project) {
                    return $er->createQueryBuilder('e')
                        ->innerJoin('e.projects', 'p')
                        ->where('p.id = :projectId')
                        ->setParameter('projectId', $project->getId());
                },
                "required" => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            "project" => null,
        ]);
    }
}
