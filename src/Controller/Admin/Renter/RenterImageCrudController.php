<?php

namespace App\Controller\Admin\Renter;

use App\Controller\Admin\Field\VichImageField;
use App\Entity\Renter\Media\RenterImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class RenterImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RenterImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield VichImageField::new('imageFile', 'Изображение')
                            ->setFormTypeOption('allow_delete', false);
    }
}