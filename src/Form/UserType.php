<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
            ])
            ->add('age', NumberType::class, [ // Ajout du champ number
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'âge ne peut pas être vide']),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 120,
                        'notInRangeMessage' => 'L\'âge doit être compris entre {{ min }} et {{ max }}',
                    ]),
                ],
                'label' => 'Âge',
            ])
            ->add('phone', TelType::class, [ // Ajout du champ phone
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro de téléphone ne peut pas être vide']),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]*$/',
                        'message' => 'Le numéro de téléphone doit être valide',
                    ]),
                ],
                'label' => 'Numéro de téléphone',
            ])
            ->add('address', TextType::class, [ // Ajout du champ address
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'adresse ne peut pas être vide']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Adresse',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
