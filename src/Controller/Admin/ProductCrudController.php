<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('description')->hideOnIndex(),
            MoneyField::new('price')
                ->setCurrency('USD')
                ->setStoredAsCents(false),
            IntegerField::new('stock'),
            AssociationField::new('category')
                ->autocomplete(),
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = ImageField::new('image')
                ->setBasePath('images/products')
                ->setTemplatePath('@EasyAdmin/crud/field/image.html.twig');
        } else {
            $fields[] = FormField::addPanel('Product Image');
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'allow_delete' => true,
                    'delete_label' => 'Remove current image',
                ]);
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Product')
            ->setEntityLabelInPlural('Products')
            ->setPageTitle('index', 'Manage Products')
            ->setPageTitle('new', 'Add New Product')
            ->setDefaultSort(['id' => 'DESC']);
    }
}
