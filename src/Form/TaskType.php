<?php

namespace App\Form;
use App\Entity\Task;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('date_start', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_end', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('financial_details', TextType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('is_private')
            ->add('project', EntityType::class, [
                // looks for choices from this entity
                'class' => Project::class,
                'attr' => ['class' => 'uk-select'],
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
