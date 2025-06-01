<?php

// this is used to show the user account info

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;
 


class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(OrderRepository $orderRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in.');
        }

        // Appel à la méthode personnalisée
        $orders = $orderRepo->findOrdersWithItemsByUser($user);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}

?>