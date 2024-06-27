<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
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
    public function show(string $slug,TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBySlugWithImages($slug);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }


}
