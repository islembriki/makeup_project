<?php
// src/Controller/TrendingController.php
namespace App\Controller;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrendingController extends AbstractController
{
    #[Route('/trending', name: 'app_trending')]
    public function index(ProductRepository $productRepository): Response
    {
        $trendingProducts = $productRepository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('trendingtemp.html.twig', [
            'trendingProducts' => $trendingProducts,
        ]);
    }
}