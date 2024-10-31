<?php

namespace App\Controller\Admin\Advertisement;

use App\Entity\Advertisement\Form\AdvertisementProperty;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class AdvertisementPropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdvertisementProperty::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('startedAt', 'Дата начала')
                            ->setColumns(6);

        yield DateTimeField::new('endedAt', 'Дата конца')
                            ->setColumns(6);

        yield AssociationField::new('terminals', 'Терминалы');

        yield IntegerField::new('displayOrder', 'Порядок отображения')
                            ->setColumns(6);
    }
}