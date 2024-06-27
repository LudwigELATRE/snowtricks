<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ){}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Snowtricks')
            ->setFaviconPath('assets/logo/logo2.jpg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Trick', 'fa-solid fa-person-snowboarding', Trick::class);
        yield MenuItem::linkToCrud('Image', 'fa-solid fa-image', Image::class);
        yield MenuItem::linkToCrud('Contact', 'fa-solid fa-address-book', Contact::class);
        yield MenuItem::linkToUrl('Retour au site', 'fa-solid fa-rotate-left', '/');

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
