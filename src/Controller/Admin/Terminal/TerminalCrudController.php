<?php

namespace App\Controller\Admin\Terminal;

use App\Entity\Terminal\Terminal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class TerminalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Terminal::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action->setLabel('Создать терминал');
                    });
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

        yield TextEditorField::new('advertisementsToString', 'Реклама')
                     ->onlyOnIndex();
    }
}