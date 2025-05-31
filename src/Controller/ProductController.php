<?php

//to show the products

namespace App\Controller;
use App\Entity\Product;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
    // Thanks to Symfony's param converter, $product is automatically fetched by id
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
}

}

?>