<?php

namespace App\Controller\Admin\Renter;

use App\Controller\Admin\Field\VichImageField;
use App\Entity\Renter\Renter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class RenterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Renter::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Создать арендатора');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {   return $crud
        ->setEntityLabelInSingular('Арендатор')
        ->setEntityLabelInPlural('Арендаторы')
        ->setPageTitle('new', 'Добавление арендатора')
        ->setPageTitle('edit', 'Изменение арендатора');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield TextField::new('name', 'Название')->setColumns(2);
        yield AssociationField::new('floor', 'Этаж')->setColumns(2);
        yield AssociationField::new('category', 'Категория')->setColumns(2);

        yield TextField::new('phoneNumber', 'Номер телефона')->onlyOnForms()->setColumns(2);
        yield TextEditorField::new('description', 'Описание')->onlyOnForms()->setColumns(8);

        yield VichImageField::new('logoFile', 'Логотип')
            ->setFormTypeOption('allow_delete', false)
            ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                </div>
            ')
            ->onlyOnForms();
        yield VichImageField::new('logo', 'Логотип')
            ->onlyOnIndex();

        yield CollectionField::new('images', 'Фотографии')
            ->onlyOnForms()
            ->setColumns(9)
            ->useEntryCrudForm(RenterImageCrudController::class);

        #yield CollectionField::new('images', 'Фотографии')
        #    ->setEntryType(VichImageForm::class)->onlyOnForms();

        yield DateTimeField::new('updatedAt', 'Обновлено')
            ->onlyOnIndex();
    }
}