<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Files;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                // looks for choices from this entity
                'class' => Project::class,
                'attr' => ['class' => 'uk-select'],
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
                'label' => 'Lier le document au projet : ',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            // ->add('task', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => Task::class,
            //     // 'attr' => ['class' => 'uk-select'],
            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'title',
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     'expanded' => true,
            // ])
            ->add('brochure', FileType::class, [
                'label' => 'Veuillez séléctionner un document PDF (.pdf ou .xpdf)  ►  ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr' => ['class' => 'uk-button uk-button-default'],
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader un document valide.',
                    ])
                ],
            ])
            ->add('summary', TextType::class, [
                'attr' => ['class' => 'uk-input'],
                'label' => 'Description brève du fichier : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
