<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Comment',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a comment',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Votre commentaire doit comporter au moins {{ limite }} caractères.',
                        'max' => 1000,
                        'maxMessage' => 'Votre commentaire ne peut pas dépasser {{ limite }} caractères',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Ecrivez votre commentaire ici',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Poster le commentaire',
                'attr' => ['class' => 'btn btn-primary btn-sm']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
