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
// New route for updating all quantities at once
    #[Route('/cart/update-all', name: 'cart_update_all')]
    public function updateAll(Request $request, CartService $cartService): Response
    {
        $quantities = $request->request->all('quantities') ?: [];

        foreach ($quantities as $productId => $quantity) {
            $quantity = (int) $quantity;
            if ($quantity > 0) {
                $cartService->setQuantity($productId, $quantity);
            } else {
                $cartService->remove($productId);
            }
        }

        $this->addFlash('success', 'Cart updated successfully!');
        return $this->redirectToRoute('cart_index');
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

    // New route for adding to cart and continuing shopping
    #[Route('/cart/add-continue/{id}', name: 'cart_add_continue')]
    public function addAndContinue(Request $request, $id, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);
        $cartService->add($id, $quantity);

        // Add success message
        $this->addFlash('success', 'Product added to cart successfully!');

        // Redirect back to the product page or products list
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        // Fallback to products list if no referer
        return $this->redirectToRoute('product_list'); // Adjust route name as needed
    }
    
    // New route for adding to cart and going to checkout
    #[Route('/cart/add-checkout/{id}', name: 'cart_add_checkout')]
    public function addAndCheckout(Request $request, $id, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);
        $cartService->add($id, $quantity);

        // Redirect directly to checkout
        return $this->redirectToRoute('order_checkout');
    }
}
