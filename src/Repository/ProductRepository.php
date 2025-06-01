<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * Get all products by category
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * Flexible filter for products by multiple optional fields
     */
    public function filterProducts(?int $categoryId = null, ?string $name = null, ?float $minPrice = null, ?float $maxPrice = null, ?string $priceOrder = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.category', 'c');

        if ($categoryId !== null) {
            $qb->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId);
        }

        if ($name !== null) {
            $qb->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%');
        }

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
            ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
        }

        if (in_array($priceOrder, ['ASC', 'DESC'])) {
            $qb->orderBy('p.price', $priceOrder);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find one product by exact name
     */
    public function findOneByName(string $name): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get all products ordered by price ASC or DESC
     */
    public function findAllOrderedByPrice(string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.price', $order)
            ->getQuery()
            ->getResult();
    }


    public function getAllCategoriesUsed(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT c
                FROM App\Entity\Category c
                JOIN c.products p
                ORDER BY c.name ASC'
            )
            ->getResult();
    }




}
