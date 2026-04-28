<?php

namespace App\Controller\Admin;

use App\Entity\ActiviteType;
use App\Entity\Bilan;
use App\Entity\Booklet;
use App\Entity\Ecf;
use App\Entity\Formation;
use App\Entity\Grade;
use App\Entity\Skill;
use App\Entity\Slot;
use App\Entity\TypeFormation;
use App\Entity\User;
use App\Entity\Vacancy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Livret C2RT');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Formations');
        yield MenuItem::linkToCrud('Formations', 'fa fa-graduation-cap', Formation::class);
        yield MenuItem::linkToCrud('Types formation', 'fa fa-tags', TypeFormation::class);
        yield MenuItem::linkToCrud('Activites types', 'fa fa-list', ActiviteType::class);
        yield MenuItem::linkToCrud('Creneaux', 'fa fa-clock', Slot::class);
        yield MenuItem::section('Livrets');
        yield MenuItem::linkToCrud('Livrets', 'fa fa-book', Booklet::class);
        yield MenuItem::linkToCrud('ECF', 'fa fa-file-alt', Ecf::class);
        yield MenuItem::linkToCrud('Bilans', 'fa fa-chart-bar', Bilan::class);
        yield MenuItem::linkToCrud('Vacances', 'fa fa-calendar', Vacancy::class);
        yield MenuItem::section('Referentiels');
        yield MenuItem::linkToCrud('Competences', 'fa fa-star', Skill::class);
        yield MenuItem::linkToCrud('Grades', 'fa fa-medal', Grade::class);
        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
    }
}
