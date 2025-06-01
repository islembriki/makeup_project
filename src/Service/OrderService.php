<?php
namespace App\Service; 

//creation d'une commande

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class OrderService
{
    private $em;
    private $cartService;
    private $security;

    //constructeur: injecte les services necessaires
    public function __construct(EntityManagerInterface $em, CartService $cartService, Security $security)
    {
        $this->em = $em; // bdd
        $this->cartService = $cartService; //the articles we're gonna use
        $this->security = $security; // the connected user
    }

    public function createOrder(): Order
    {
        $order = new Order(); 
        $order->setUser($this->security->getUser()); 
        $order->setCreatedAt(new \DateTime()); //date de creation de la commande
    
        //parcours des articles du panier 
        foreach ($this->cartService->getCart() as $item){
            $orderItem = new OrderItem(); //un item de la commande
            $orderItem->setProduct($item['product']); // Produit associé à l'item
            $orderItem->setQuantity($item['quantity']); // Quantité achetée
            $orderItem->setPrice($item['product']->getPrice()); // Prix unitaire
            $orderItem->setOrderref($order); // Lien avec la commande
            $this->em->persist($orderItem); // Prépare l’enregistrement
        }

        $this->em->persist($order); // On prépare l’objet commande
        $this->em->flush(); //Envoie tout en base

        $this->cartService->clear(); // Vider le panier une fois la commande passée

        return $order;
    }
}

?>