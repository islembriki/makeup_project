<?php
// src/EventListener/LogoutListener.php
namespace App\EventListener;

use App\Service\CartService;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function onLogout(LogoutEvent $event): void
    {
        // Clear the cart when user logs out
        $this->cartService->clear();
    }
}
