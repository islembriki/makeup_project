<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField,
    MoneyField, IntegerField, AssociationField,
    ImageField, FormField
};
class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        IdField::new('id')->hideOnForm(),
        TextField::new('name'),
        TextEditorField::new('description')->hideOnIndex(),
        MoneyField::new('price')->setCurrency('USD'),
        IntegerField::new('stock'),
        AssociationField::new('category'),

        // Image configuration
        ImageField::new('image')
            ->setBasePath('images/products')
            ->onlyOnIndex(),

        FormField::addPanel('Image Upload'),
        TextField::new('imageFile')
            ->setFormType(VichImageType::class)
            ->onlyOnForms()
            ->setFormTypeOptions([
                'allow_delete' => false,
            ]),
    ];
    }
}
