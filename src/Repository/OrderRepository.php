<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;  // Add this import
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    //displays later on all the history of commands a user did
    public function findOrdersWithItemsByUser(User $user): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'oi')  // Changed from 'o.orderItems' to 'o.items'
            ->addSelect('oi')
            ->leftJoin('oi.product', 'p')
            ->addSelect('p')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC')  // Changed from 'o.CreatedAt' to 'o.createdAt'
            ->getQuery()
            ->getResult();
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
