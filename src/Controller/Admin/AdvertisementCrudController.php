<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\VichFileField;
use App\Entity\Advertisement;
use App\Entity\AdvertisementProperty;
use App\Form\Type\AdvertisementPropertiesForm;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;


class AdvertisementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Advertisement::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Создать рекламу');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {   return $crud
        ->setEntityLabelInSingular('Реклама')
        ->setEntityLabelInPlural('Реклама')
        ->setPageTitle('new', 'Добавление рекламы')
        ->setPageTitle('edit', 'Изменение рекламы');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield VichFileField::new('advertisementFile', 'Файл')
            ->setFormTypeOption('allow_delete', false)
            ->setRequired(true)
            ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                    <span class="badge badge-info">*.jiff</span>
                    <span class="badge badge-info">*.webp</span>
                </div>
            ')
            ->onlyOnForms();

        yield VichFileField::new('advertisementFile', 'Файл')
            ->setFormTypeOption('allow_delete', false)
            ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                    <span class="badge badge-info">*.jiff</span>
                    <span class="badge badge-info">*.webp</span>
                </div>
            ')
            ->onlyWhenUpdating();

        yield VichFileField::new('advertisement', 'Файл')
            ->onlyOnIndex();

        yield CollectionField::new('properties', 'Настройки')
            ->showEntryLabel(false)
            ->useEntryCrudForm(AdvertisementPropertyCrudController::class);

        yield BooleanField::new('canShow', 'Показывать');
        yield DateTimeField::new('updatedAt', 'Обновлено')
            ->onlyOnIndex();
    }
}