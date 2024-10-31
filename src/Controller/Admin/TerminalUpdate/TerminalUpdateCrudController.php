<?php

namespace App\Controller\Admin\TerminalUpdate;

use App\Controller\Admin\Field\VichFileField;
use App\Entity\TerminalUpdate\TerminalUpdate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class TerminalUpdateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TerminalUpdate::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $updates = new ArrayCollection($entityManager->getRepository(TerminalUpdate::class)->findAll());

        /** @var TerminalUpdate $entityInstance */
        $filtered = $updates->filter(function (TerminalUpdate $update) use ($entityInstance) {
            return $update->getVersion() >= $entityInstance->getVersion();
        });

        if ($filtered->isEmpty()) {
            parent::persistEntity($entityManager, $entityInstance);
            return;
        }

        $this->addFlash('warning', 'Версию меньше или равную текущей - поставить нельзя.');
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var TerminalUpdate $entityInstance */
        if ($entityInstance->getTerminals()->isEmpty()) {
            parent::deleteEntity($entityManager, $entityInstance);
            return;
        }

        $this->addFlash('warning', 'Нельзя удалить данное обновление, так как к нему привязаны терминалы.');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                    ->remove(Crud::PAGE_INDEX, Action::EDIT)
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action->setLabel('Создать обновление');
                    });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
                 ->setEntityLabelInSingular('Обновление')
                 ->setEntityLabelInPlural('Обновления')
                 ->setPageTitle('new', 'Добавить новое обновление')
                 ->showEntityActionsInlined();
    }
    #Нельзя удалить данное обновление, так как к нему привязаны терминалы.
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
                     ->onlyOnIndex();

        yield TextareaField::new('description', 'Описание обновления');

        yield VichFileField::new('updateFile', 'Архив с обновлением')
                     ->setRequired(true)
                     ->onlyOnForms();

        yield VichFileField::new('update', 'Обновление')
                     ->onlyOnIndex();

        yield NumberField::new('version', 'Версия');

        yield ChoiceField::new('type', 'Тип')
                     ->setChoices([
                         'Модифицированная версия' => 'Modified version',
                         'Стабильная версия' => 'Stable version',
                     ]);

        yield ArrayField::new('terminals', 'Терминалы')
                     ->onlyOnIndex();

        yield DateTimeField::new('createdAt', 'Создана')
                     ->onlyOnIndex();
    }
}