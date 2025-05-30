<?php
namespace App\Controller; 

//c'est le controlleur du panier

use App\Service\CartService; //importation du service qui gere la logique du panier
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('carte',name: 'cart_index')]
    public function index(CartService $cartService):Response
    {
        //recuperation des articles du paniers et le total
        return $this->render('cart/index.html.twig',
        [
            'items' => $cartService->getCart(), //liste des produits + quantités
            'total' => $cartService->getTotal(), //prix total du panier
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, CartService $cartService):Response
    {
        //ajout 1 quantité du produit avec l'ID donné
        $cartService->add($id);

        //redirection vers la page du panier après ajout
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartService $cartService):Response
    {
        //suppression totale du produit du panier (quel que soit sa quantité)
        $cartService->remove($id);

        //redirection vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/decrement/{id}', name: 'cart_decrement')]
    public function decrement($id, CartService $cartService):Response
    {
        //diminue la quantité du produit de 1 (et le retire si quantité 0)
        $cartService->decrement($id);

        //redirection vers la page du panier
        return $this->redirectToRoute('cart_index');
    }
}






?>