<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom ne peut pas être vide']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Nom',
            ])
            ->add('bio', TextareaType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La biographie ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Biographie',
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'male',
                    'Femme' => 'female',
                    'Autre' => 'other',
                ],
                'expanded' => true, // Affiche des boutons radio
                'label' => 'Genre',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un genre']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
