<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Floor;
use App\Entity\Renter;
use App\Entity\StandbyMode;
use App\Entity\TerminalUpdate;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Service\Attribute\Required;


#[IsGranted("ROLE_ADMIN")]
class DashboardController extends AbstractDashboardController
{
    #[Required]
    public AdminUrlGenerator $adminUrlGenerator;

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Админ-панель')
            ->setDefaultColorScheme('dark');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Арендаторы');
        yield MenuItem::linkToCrud('Категории', 'fa-solid fa-tag', Category::class);
        yield MenuItem::linkToCrud('Арендаторы', 'fa-solid fa-users', Renter::class);

        yield MenuItem::section('Карта');
        yield MenuItem::linkToCrud('Режим ожидания', 'fa-solid fa-tv', StandbyMode::class);
        yield MenuItem::linkToCrud('Этажи', 'fa-solid fa-stairs', Floor::class);


        yield MenuItem::section('Софт');
        yield MenuItem::linkToCrud('Обновления', 'fa-solid fa-list-ul', TerminalUpdate::class);


        yield MenuItem::section('Настройки');
        yield MenuItem::linkToCrud('Пользователи', 'fa-solid fa-user-gear', User::class)
                    ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToUrl('API', 'fa fa-link', 'api')
                    ->setLinkTarget('_blank')
                    ->setPermission('ROLE_ADMIN');
    }
}
