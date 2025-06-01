<?php
namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;


class CartService
{
    private SessionInterface $session;
    private ProductRepository $productRepository;
    private  Security $security;

    public function __construct(SessionInterface $session, ProductRepository $productRepository, Security $security)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->security = $security;
    }

    // Get user-specific cart key
    private function getCartKey(): string
    {
        $user = $this->security->getUser();
        
        if ($user instanceof \App\Entity\User) { //check if we have an actual user or guest
            $userId = $user->getId();
        } else {
            $userId = 'guest';
        }
        return 'cart_' . $userId;
    }

    // addss product to cart 
    public function add(int $productId, int $quantity = 1): void
    {
        $cartKey = $this->getCartKey();
        $cart = $this->session->get($cartKey, []);

        if (!isset($cart[$productId])) {
            $cart[$productId] = 0;
        }
        $cart[$productId] += $quantity;

        $this->session->set($cartKey, $cart);
    }

    // set/change exact quantity for a product
    public function setQuantity(int $productId, int $quantity): void
    {
        $cartKey = $this->getCartKey();
        $cart = $this->session->get($cartKey, []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $this->session->set($cartKey, $cart);
    }

    
    public function remove(int $productId): void
    {
        $cartKey = $this->getCartKey();
        $cart = $this->session->get($cartKey, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $this->session->set($cartKey, $cart);
    }

   //decrease quantity (by 1 dima)
    public function decrement(int $productId): void
    {
        $cartKey = $this->getCartKey();
        $cart = $this->session->get($cartKey, []);

        if (isset($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
            }
        }

        $this->session->set($cartKey, $cart);
    }

    
    public function getCart(): array
    {
        $cartKey = $this->getCartKey();
        $cart = $this->session->get($cartKey, []);
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartWithData;
    }

    // total price
    public function getTotal(): float
    {
        $total = 0;
        $cart = $this->getCart();

        foreach ($cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    
    public function clear(): void
    {
        $cartKey = $this->getCartKey();
        $this->session->remove($cartKey);
    }
}
