<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FollowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('users', EntityType::class, [
            'attr' => ['class' => 'ui fluid selection dropdown'],
            // 'multiple' => true,
            'choice_label' =>'name',
            // 'expanded' => true,
            'class' => User::class,
            
            'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            }
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             'data_class' => null,
        ]);
    }
}