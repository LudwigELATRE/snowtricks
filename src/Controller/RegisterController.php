<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\SendEmailService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }


    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, SendEmailService $emailService)
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
            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                //$emailService->sendEmail();

                $this->addFlash('success', 'Votre compte a bien été créé.');
                return $this->redirectToRoute('login');
            } catch (UniqueConstraintViolationException $e) {
                $this->logger->error('Erreur de duplication : ' . $e->getMessage());
                $this->addFlash('error', 'Cet email est déjà utilisé. Veuillez en choisir un autre.');
                return $this->redirectToRoute('register');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la persistance de l\'utilisateur : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.');
                return $this->redirectToRoute('register');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
