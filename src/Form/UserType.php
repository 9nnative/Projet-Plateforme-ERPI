<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('forename', TextType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('school', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'uk-input'] 
            ])
            ->add('bio', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'uk-input'] 
            ])
            // ->add('profilepicpath', TextType::class, [
            //     'attr' => ['class' => 'uk-input'] 
            // ])
            ->add('type', EntityType::class, [
                // looks for choices from this entity
                'class' => Type::class,
                'attr' => ['class' => 'uk-select'],
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            // ->add('follow')
            // ->add('attrtask')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
