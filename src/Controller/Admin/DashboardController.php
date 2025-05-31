<?php
// src/Controller/Admin/DashboardController.php     
// Ce fichier est responsable de la configuration du tableau de bord d'administration (partie amira)
// Il utilise EasyAdminBundle pour créer une interface d'administration personnalisée
// Il gère les entités Product, Category, User, Order et OrderItem

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

//importe les types de champs nécessaires pour le formulaire d'upload d'image
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;



#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{

    // cette méthode index est appelée pour afficher le tableau de bord d'administration
    public function index(): Response   
    {           //ce twig template est utilisé pour customiser l'affichage de tableau de bord d'admin
        
       return $this->render('admin/customdashboard.html.twig', [              
        'dashboard_title' => 'Makeup Project',
    ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Makeup Project');
    }
  // cette méthode configure les elts du menu de navigation dans le tableau de bord d'administration
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Products', 'fa fa-box', Product::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list',Category::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-user-circle',User::class);
        yield MenuItem::linkToCrud('orders','fa fa-shopping-cart',Order::class);
        yield MenuItem::linkToCrud('orderItems','fa fa-tag',OrderItem::class);
        yield MenuItem::linkToRoute('User Page', 'fa fa-user', 'product_list');       //takes you to the product list page
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out-alt', 'app_logout')      //link to the loggin page
            ->setCssClass('nav-link text-danger');  //red style lel logout
        
    }
 
    // cette méthode configure les champs à afficher dans le formulaire de création et d'édition des entités
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextareaField::new('description')->hideOnIndex(),
            MoneyField::new('price')->setCurrency('USD'),
            IntegerField::new('stock'),
            AssociationField::new('category'),
            ImageField::new('imageName')
                ->setBasePath('images/products')
                ->setUploadDir('public/images/products')
                ->onlyOnIndex(),
            FormField::addPanel('Image Upload'),
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
        ];
    }


}
