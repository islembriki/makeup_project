<?php
namespace App\Controller;

//gerer les commandes

use App\Service\OrderService;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/place', name: 'order_place')]
    public function placeOrder(OrderService $orderService): Response
    {
        //creation d'une commande depuis le panier
        $orderService->createOrder();

        //redirection vers les commandes de l'utilisateur
        return $this->redirectToRoute('user_orders');
    }

    #[Route('/my-orders', name: 'user_orders')]
    public function myOrders(OrderRepository $orderRepo): Response
    {
        //recherche des commandes de l'utilisateur actuel
        $orders = $orderRepo->findOrdersWithItemsByUser(['user' => $this->getUser()]);

        //afficher la liste dans le template
        return $this->render('order/my_orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}

?>