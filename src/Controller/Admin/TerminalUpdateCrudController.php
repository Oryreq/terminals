<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\VichFileField;
use App\Entity\TerminalUpdate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class TerminalUpdateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TerminalUpdate::class;
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

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('description', 'Описание обновления');

        yield VichFileField::new('updateFile', 'Архив с обновлением')
                        ->onlyOnForms();

        yield VichFileField::new('update', 'Обновление')
                        ->onlyOnIndex();

        yield TextField::new('version', 'Версия');

        yield ChoiceField::new('type', 'Тип')->setChoices([
            'Модифицированная версия' => 'Modified version',
            'Стабильная версия' => 'Stable version',
        ]);

        yield DateTimeField::new('createdAt', 'Создана')->hideOnForm();
    }
}