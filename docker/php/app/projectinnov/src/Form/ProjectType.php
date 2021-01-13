<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjectType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $today = new \DateTime();
        $builder
        ->add('title', TextType::class, [
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('theme', TextType::class, [
            'attr' => ['class' => 'uk-input']
        ])
        ->add('date_start', DateType::class, [
            'widget' => 'single_text',
        ])
        ->add('date_end', DateType::class, [
            'widget' => 'single_text',
        ])
        ->add('summary',TextType::class, [
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('fincancial_details', MoneyType::class, [
            'divisor' => 100,
            'required' => false,
        ])
        ->add('content', CKEditorType::class, [
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('is_private', CheckBoxType::class,[
        'required' => false],
    );

        }

}