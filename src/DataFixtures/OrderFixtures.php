<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Assume users are already added in UserFixtures
        $user = $this->getReference('user_aziza123@gmail.com',User::class); // You need to define this in UserFixtures
        for ($i = 0; $i < 3; $i++) {
            $order = new Order();
            $order->setUser($user);
            //$order->setCreatedAt(new \DateTime());
            $order->setStatus('pending');
            $order->setTotal(0); // Will be updated when adding items

            $this->addReference('order_' . $i, $order);
            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
