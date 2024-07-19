<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use App\Form\TrickType;
use App\Form\UserType;
use App\Repository\TrickRepository;
use App\Service\UploadImage;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/my-tricks', name: 'trick_show')]
    public function show(TrickRepository $trickRepository, Security $security): Response
    {
        // Obtenir l'utilisateur actuellement connecté
        $user = $security->getUser();

        // Si l'utilisateur n'est pas connecté, rediriger ou gérer l'erreur
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view your tricks.');
        }

        // Récupérer les tricks de l'utilisateur connecté
        $tricks = $trickRepository->findAllWithImagesByUserId($user->getId());
        return $this->render('account/show.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    #[Route('/add/tricks', name: 'add')]
    public function add(string $slug,TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneByslug($slug);

        return $this->render('Account/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route('/trick/delete/{id}', name: 'trick_delete')]
    public function deleteTrick(int $id, TrickRepository $trickRepository): Response
    {
        $trickRepository->removeTrickAndAssociations($id);

        return $this->redirectToRoute('trick_show');
    }

    #[Route('/edit', name: 'account_edit', methods: ['GET', 'POST'])]
    public function editAccount(Request $request, EntityManagerInterface $entityManager, UploadImage $uploadImage, Security $security): Response {
        $user = $this->getUser();
        $currentAvatarPath = $user->getAvatar();
        $image = new Image();

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('user', UserType::class, [
            'data' => $user,
        ]);
        $formBuilder->add('image', ImageType::class, [
            'data' => $image,
            'mapped' => false,
        ]);
        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'btn btn-primary btn-lg']
        ]);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->get('file')->getData();

            if ($imageFile) {
                $image->setFile($imageFile);
                $savedImage = $uploadImage->saveImage($image, "avatars");
                if ($savedImage) {
                    $user->setAvatar($savedImage->getPath());
                }
            }else {
                $user->setAvatar($currentAvatarPath);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Account/account_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/trick', name: 'trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager, Security $security, UploadImage $uploadImage): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($this->submitForm($form,$trick, $entityManager, $security, $uploadImage)) {
            return $this->redirectToRoute('account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/trick_new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}/update', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function editTrick(string $slug,TrickRepository $trickRepository, Request $request, EntityManagerInterface $entityManager, Security $security, UploadImage $uploadImage): Response
    {
        $trick = $trickRepository->findOneByslug($slug);
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($this->submitForm($form,$trick, $entityManager, $security, $uploadImage)) {
            return $this->redirectToRoute('trick_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Account/trick_update.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    private function submitForm($form,Trick $trick, EntityManagerInterface $entityManager, Security $security, UploadImage $uploadImage): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $createdAt = new DateTimeImmutable();
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($trick->getTitle())->lower();
            $trick->setSlug($slug);
            $trick->setUser($user);
            $trick->setCreatedAt($createdAt);
            $url = $this->getYoutubeVideoId($trick->getUrlYoutube());
            $trick->setUrlYoutube('https://www.youtube.com/embed/'.$url);

            // Gestion des images
            foreach ($trick->getImages() as $image) {
                $name = $slugger->slug($image->getCaption())->lower();
                $image->setTrick($trick);
                $image->setName($name);
                $image = $uploadImage->saveImage($image, "tricks");
                $entityManager->persist($image);
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            return true;
        }
        return false;
    }

    private function getYoutubeVideoId($url): ?string {
    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}
}
