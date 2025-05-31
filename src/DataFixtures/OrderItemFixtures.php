<?php

namespace App\DataFixtures;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $order = $this->getReference('order_' . $i,Order::class);;

            // Add 2 items per order
            for ($j = 0; $j < 2; $j++) {
                $product = $this->getReference('product_' . $j,Product::class); // Define in ProductFixtures
                $quantity = rand(1, 5);

                $item = new OrderItem();
                $item->setOrderref($order);
                $item->setProduct($product);
                $item->setQuantity($quantity);
                $item->setPrice($product->getPrice());

                $manager->persist($item);
                $order->addItem($item); // Automatically updates total
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class,
        ];
    }
}
