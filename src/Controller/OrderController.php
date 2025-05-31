<?php
namespace App\Controller;

//gerer les commandes

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
        $orders = $orderRepo->findOrdersWithItemsByUser($this->getUser());



        //afficher la liste dans le template
        return $this->render('order/my_orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/checkout', name: 'order_checkout')]
    public function checkout(
        EntityManagerInterface $entityManager,
        CartService $cartService,
        Security $security
    ): Response {
        // 1. Get the current user
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to checkout.');
        }

        //2. Get cart items
        $cartItems = $cartService->getCart();
        if (empty($cartItems)) {
           $this->addFlash('warning', 'Your cart is empty.');
           return $this->redirectToRoute('cart_index'); // adjust route if needed
        }

        // 3. Create the Order
        $order = new Order();
        $order->setUser($user);

        // 4. Add OrderItems
        foreach ($cartItems as $cartItem) {
            $product = $cartItem['product'];
            $quantity = $cartItem['quantity'];

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setOrderref($order);

            $order->addItem($orderItem);
            $entityManager->persist($orderItem); // Make sure items are persisted
        }

        // 5. Persist and flush Order
        $entityManager->persist($order);
        $entityManager->flush();

        // 6. Clear the cart
        $cartService->clear();

        // 7. Render confirmation
        return $this->render('order/checkout.html.twig', [
            'order' => $order
        ]);
    }




}
?>
