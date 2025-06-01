<?php

//to show the products

namespace App\Controller;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    #[Route('/products', name: 'product_list')]
    public function list(ProductRepository $productRepository, Request $request): Response
    {
        // cast query parameters so we don't face type problems
        $name = $request->query->get('name');
        $categoryIdParam = $request->query->get('category');
        $categoryId = is_numeric($categoryIdParam) ? (int)$categoryIdParam : null;

        $minPriceParam = $request->query->get('minPrice');
        $minPrice = is_numeric($minPriceParam) ? (float)$minPriceParam : null;

        $maxPriceParam = $request->query->get('maxPrice');
        $maxPrice = is_numeric($maxPriceParam) ? (float)$maxPriceParam : null;

        $priceOrder = strtoupper($request->query->get('priceOrder', '')); 
        if (!in_array($priceOrder, ['ASC', 'DESC'])) {
            $priceOrder = null;
        }

        // Get all categories from the db for dropdown
        $categories = $productRepository->getAllCategoriesUsed();

        // get filtered products with the filterProducts method
        $products = $productRepository->filterProducts($categoryId, $name, $minPrice, $maxPrice, $priceOrder);

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'search_name' => $name,
            'search_category' => $categoryId,
            'search_minPrice' => $minPrice,
            'search_maxPrice' => $maxPrice,
            'search_priceOrder' => $priceOrder,
        ]);
    }


    //selectionner un produit pour afficher son detail 
    #[Route('/products/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }



}

?>
