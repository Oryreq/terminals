<?php

namespace App\Controller\Admin\Category;

use App\Controller\Admin\Field\VichFileField;
use App\Entity\Category\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action->setLabel('Создать категорию');
                    });
    }

    public function configureCrud(Crud $crud): Crud
    {   return $crud
                ->setEntityLabelInSingular('Категория')
                ->setEntityLabelInPlural('Категории')
                ->setPageTitle('new', 'Добавление категории')
                ->setPageTitle('edit', 'Изменение категории');
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
                        ->onlyOnIndex();

        yield TextField::new('name', 'Название');

        yield VichFileField::new('imageFile', 'Изображение')
                        ->setFormTypeOption('allow_delete', false)
                        ->setRequired(true)
                        ->setHelp('
                <div class="mt-3">
                    <span class="badge badge-info">*.jpg</span>
                    <span class="badge badge-info">*.jpeg</span>
                    <span class="badge badge-info">*.png</span>
                </div>
            ')
                        ->onlyOnForms();
        yield VichFileField::new('image', 'Изображение')
                        ->onlyOnIndex();

        yield ArrayField::new('renters', 'Арендаторы')
                        ->onlyOnIndex();
        yield DateTimeField::new('updatedAt', 'Обновлено')
                        ->onlyOnIndex();
    }

}