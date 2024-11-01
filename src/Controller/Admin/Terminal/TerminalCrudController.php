<?php

namespace App\Controller\Admin\Terminal;

use App\Entity\Terminal\Terminal;
use App\Entity\TerminalUpdate\TerminalUpdate;
use App\Repository\Terminal\TerminalRepository;
use App\Repository\TerminalUpdate\TerminalUpdateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;


class TerminalCrudController extends AbstractCrudController
{
    #[Required]
    public TerminalRepository $terminalRepository;

    #[Required]
    public TerminalUpdateRepository $updateRepository;

    #[Required]
    public EntityManagerInterface $entityManager;

    private string $crudUrl = '';

    public function __construct(private readonly AdminUrlGenerator $adminUrlGenerator)
    {
        $this->crudUrl = $this->adminUrlGenerator
                              ->setController(TerminalCrudController::class)
                              ->setAction(Action::INDEX)
                              ->generateUrl();
    }


    public static function getEntityFqcn(): string
    {
        return Terminal::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $setLastStableUpdateAction = Action::new('setLastStableVersion', 'Поставить последнюю стабильную версию для каждого терминала')
                        ->linkToCrudAction('setLastStableVersion')
                        ->setCssClass('btn btn-secondary btn-labeled btn-labeled-right action-filters-button action-filters-applied')
                        ->createAsGlobalAction();

        return $actions
                    ->add(Crud::PAGE_INDEX, $setLastStableUpdateAction)
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action->setLabel('Создать терминал');
                    });
    }


    public function setLastStableVersion(AdminContext $context): Response
    {
        $updates = new ArrayCollection($this->updateRepository->findAll());
        $stableUpdates = $updates->filter(function (TerminalUpdate $update) {
           return str_contains($update->getType(), 'Stable');
        });

        if ($stableUpdates->isEmpty()) {
            $this->addFlash('warning', 'Нет стабильной версии в обновлениях.');
            return $this->redirect($this->crudUrl);
        }


        $stableVersions = $stableUpdates->map(function (TerminalUpdate $update) {
            return $update->getVersion();
        });

        $lastStableVersion = max($stableVersions->toArray());
        $lastStableUpdate = $stableUpdates->filter(function (TerminalUpdate $update) use ($lastStableVersion) {
            return $update->getVersion() == $lastStableVersion;
        })->first();

        $terminals = new ArrayCollection($this->terminalRepository->findAll());
        foreach ($terminals as $terminal) {
            $terminal->setTerminalUpdate($lastStableUpdate);
            $this->entityManager->persist($terminal);
        }

        $this->entityManager->flush();
        $this->addFlash('success', 'Поставлена последняя стабильная версия для каждого терминала.');
        return $this->redirect($this->crudUrl);
    }

    public function configureCrud(Crud $crud): Crud
    {   return $crud
                 ->setEntityLabelInSingular('Терминал')
                 ->setEntityLabelInPlural('Терминалы')
                 ->setPageTitle('new', 'Добавление терминала')
                 ->setPageTitle('edit', 'Изменение терминала');
    }

    public function createEntity(string $entityFqcn): Terminal
    {
        $terminal = new Terminal();
        $terminal->setSleepDelay(1);
        return $terminal;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
                     ->onlyOnIndex();

        yield FormField::addColumn(11);
        yield TextField::new('name', 'Название')
                     ->setColumns(5);

        yield IntegerField::new('sleepDelay', 'Задержка перехода в режим ожидания')
                     ->setValue(1)
                     ->setHelp('В секундах')
                     ->setColumns(5);

        yield IntegerField::new('changingAdvertisementTime', 'Время смены рекламы')
                     ->setHelp('В секундах')
                     ->setColumns(5);

        yield AssociationField::new('terminalUpdate', 'Обновление')
                     ->setColumns(5);

        $test = TextEditorField::new('advertisementsToString', 'Реклама')
                               ->getAsDto();

        yield TextEditorField::new('advertisementsToString', 'Реклама')
                     ->onlyOnIndex();
    }
}