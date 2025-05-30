<?php
namespace App\Service; 
//On est dans le dossier src/Service du projet Symfony et on crée un nouveau service appelé CartService

//c'est un panier contenant des produits
//ajouter un produit 
//supprimer un produit -> supprimer un produit totalement du panier
//diminuer la quantité d'un produit -> diminue le nbre d'exemplaires d'un meme produit dans le panier
//augmenter la quantité d'un produit -> meme role que la fonction add qui ajoute les produits dans le panier
//voir le panier complet
//voir le total des prix
//vider le panier

use Symfony\Component\HttpFoundation\Session\SessionInterface;
//On utilise la session SessionInterafce pour stocker le panier de l'utilisateur
use App\Repository\ProductRepository;
//On utilise le ProductRepository pour récupérer les informations des produits à partir de leur Id

class CartService
{
    private $session; //pour acceder au panier stiocké dans la session
    private $productRepository; //pour récupérer les produits à partir de la base de données
    
    public function __construct(SessionInterface $session, ProductRepository $productRepository) //constructeur
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    //ajouter un produit au panier
    public function add(int $productId): void 
    {
        // Récupérer le panier actuel
        $cart = $this->session->get('cart', []); //si le panier n'existe pas encore, on obtient un tableau vide 

        // Ajouter ou mettre à jour le produit dans le panier:
        if (!isset($cart[$productId])) { //si  le produit n'est pas encore dans le panier
            $cart[$productId] = 0; // on initialise sa quantité à 0
        } 
        //si le produit existe deja dans le panier (mise a jour du produit)
        $cart[$productId]++; //on incrémente la quantité du produit dans le panier


        // mise à jour du panier dans la session avec les nouvelles données
        $this->session->set('cart', $cart);
    }

    //retirer un produit du panier
    public function remove(int $productId): void
    {
        // Récupérer le panier actuel
        $cart = $this->session->get('cart', []);

        // Supprimer le produit du panier s'il existe
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        else {
            // Si le produit n'existe pas dans le panier, on ne fait rien
            return;
        }
        // Enregistrer le panier mis à jour dans la session
        $this->session->set('cart', $cart);
    }

    //diminuer la quantité d'un produit dans le panier
    public function decrement(int $productId): void
    {
        // Récupérer le panier actuel
        $cart = $this->session->get('cart', []);

        // Vérifier si le produit existe dans le panier
        if (isset($cart[$productId])) {
            // Si la quantité est supérieure à 1, on la diminue de 1
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                // Si la quantité est 1, on supprime le produit du panier
                unset($cart[$productId]);
            }
        }
        else {
            // Si le produit n'existe pas dans le panier, on ne fait rien
            return;
        }
        // Enregistrer le panier mis à jour dans la session
        $this->session->set('cart', $cart);
    }

    //voir les produits du panier
    public function getCart(): array
    {
        // Récupérer le panier actuel
        $cart=$this->session->get('cart',[]);
        //creation d'un tableau avec les propriétés des produits
        $cartWithData =[];

        foreach ($cart as $id => $quantity){
            //pour chaque produit, on recupere ses infos (nom,prix,image...) depuis la base de donnees
            $product = $this->productRepository->find($id);

            if ($product){
                //si le produit existe dans la base de données, on l'ajoute au tableau $cartWithData
                //avec ses informations et sa quantité dans le panier
                $cartWithData[]= [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartWithData; //return le tableau contenant les produits du panier avec leurs informations
    }

    //calcul du prix total 
    public function getTotal() :float
    {
        $total =0; //initialisation du total à 0
        $cart = $this->getCart(); //récupération du panier
        //parcours du panier pour calculer le total
        foreach ($cart as $item) {
            //pour chaque produit du panier, on multiplie le prix par la quantité et on l'ajoute au total
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total; //return le total des prix des produits du panier
    }

    //vider le panier (apres la fin d'une commande)
    public function clear() :void
    {
        $this->session->remove('cart'); //on supprime lepanier de la session
    }
}




?>