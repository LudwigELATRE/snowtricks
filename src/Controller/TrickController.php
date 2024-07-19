<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/tricks')]
class TrickController extends AbstractController
{

    #[Route('/', name: 'tricks')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAllWithImages();

        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    #[Route('/show/{slug}', name: 'show')]
    public function show(string $slug,TrickRepository $trickRepository,CommentRepository $commentRepository ,Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $trick = $trickRepository->findOneBySlugWithImages($slug);
        $user = $userRepository->findOneBy(['id' => $trick->getUser()]);
        $allComment = $commentRepository->findByTrickWithUser($trick->getId());
        $createdAt = new DateTimeImmutable();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt($createdAt);
            $comment->setDisabled(false);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comment_form' => $form->createView(),
            'comments' => $allComment,
            'user' => $user,
        ]);
    }

}
