<?php

namespace App\Controller\Admin\Floor;

use App\Controller\Admin\Field\VichImageField;
use App\Entity\Floor\Floor;
use App\Repository\Floor\FloorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Contracts\Service\Attribute\Required;


class FloorCrudController extends AbstractCrudController
{
    #[Required]
    public FloorRepository $floorRepository;

    private int $maxFloorNumber = 4;

    public static function getEntityFqcn(): string
    {
        return Floor::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $floorsCount = count($this->floorRepository->findAll());
        if ($floorsCount >= $this->maxFloorNumber) {
            return $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action->setLabel('Добавить этаж');
                    });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
                    ->setEntityLabelInSingular('Этаж')
                    ->setEntityLabelInPlural('Этажи')
                    ->setPageTitle('edit', 'Изменение этажа')
                    ->setPageTitle('new', 'Добавление этажа');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield IntegerField::new('floorNumber', 'Этаж')
                    ->hideWhenUpdating();
        yield VichImageField::new('imageFile', 'Изображение')
                        ->setFormTypeOption('allow_delete', false)
                        ->onlyOnForms();
        yield VichImageField::new('image', 'Изображение')->onlyOnIndex();
    }
}