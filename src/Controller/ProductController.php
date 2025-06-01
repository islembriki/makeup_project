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
        // Safely read and cast query parameters
        $name = $request->query->get('name');
        $categoryIdParam = $request->query->get('category');
        $categoryId = is_numeric($categoryIdParam) ? (int)$categoryIdParam : null;

        $minPriceParam = $request->query->get('minPrice');
        $minPrice = is_numeric($minPriceParam) ? (float)$minPriceParam : null;

        $maxPriceParam = $request->query->get('maxPrice');
        $maxPrice = is_numeric($maxPriceParam) ? (float)$maxPriceParam : null;

        $priceOrder = strtoupper($request->query->get('priceOrder', '')); // ASC or DESC
        if (!in_array($priceOrder, ['ASC', 'DESC'])) {
            $priceOrder = null;
        }

        // Get categories to populate filter dropdown
        $categories = $productRepository->getAllCategoriesUsed();

        // Fetch filtered products
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
