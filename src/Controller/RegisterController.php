<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();

        $form = $this->createForm(registerType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setValidate(true);

            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre Compte a bien été créé.');

            return $this->redirectToRoute('login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
