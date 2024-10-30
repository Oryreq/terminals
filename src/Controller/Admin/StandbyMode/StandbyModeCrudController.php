<?php

namespace App\Controller\Admin\StandbyMode;

use App\Controller\Admin\Field\VichFileField;
use App\Entity\StandbyMode\StandbyMode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;


class StandbyModeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StandbyMode::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Создать файл');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {   return $crud
        ->setEntityLabelInSingular('Режим ожидания')
        ->setEntityLabelInPlural('Режим ожидания')
        ->setPageTitle('new', 'Добавление файла')
        ->setPageTitle('edit', 'Изменение файла');
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield VichFileField::new('modeFile', 'Файл')
            ->setFormTypeOption('allow_delete', false)
            ->setRequired(true)
            ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.mp4</span>
                    <span class="badge badge-info">*.wmv</span>
                    <span class="badge badge-info">*.avi</span>
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                </div>
            ')
            ->onlyOnForms();

        yield VichFileField::new('modeFile', 'Файл')
            ->setFormTypeOption('allow_delete', false)
            ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.mp4</span>
                    <span class="badge badge-info">*.wmv</span>
                    <span class="badge badge-info">*.avi</span>
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                </div>
            ')
            ->onlyWhenUpdating();

        yield VichFileField::new('mode', 'Файл')
                ->onlyOnIndex();

        yield BooleanField::new('isVisible', 'Виден ли файл');
        yield DateTimeField::new('updatedAt', 'Обновлено')
            ->onlyOnIndex();
    }
}