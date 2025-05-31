<?php
namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Request $request, $id, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);
        $cartService->add($id, $quantity);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartService $cartService): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/decrement/{id}', name: 'cart_decrement')]
    public function decrement($id, CartService $cartService): Response
    {
        $cartService->decrement($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/update/{id}', name: 'cart_update')]
    public function update(Request $request, $id, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);

        // Use setQuantity instead of remove + add
        $cartService->setQuantity($id, $quantity);

        return $this->redirectToRoute('cart_index');
    }
}
