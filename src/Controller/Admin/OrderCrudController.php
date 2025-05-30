<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

   public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        AssociationField::new('user')->autocomplete(),
        DateTimeField::new('madeAt'),
        ChoiceField::new('status')
            ->setChoices([
                'Pending' => 'pending',
                'Processing' => 'processing',
                'Shipped' => 'shipped',
                'Delivered' => 'delivered',
                'Cancelled' => 'cancelled'
            ]),
        MoneyField::new('total')->setCurrency('USD'),
        CollectionField::new('items')
            ->useEntryCrudForm(OrderItemCrudController::class)
            ->hideOnIndex()
    ];
}
}
