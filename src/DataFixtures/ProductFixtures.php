<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Sample categories already added in CategoryFixtures with these references
        $categories = [
            'lipstick' => $this->getReference('category_lipstick', Category::class),
            'foundation' => $this->getReference('category_foundation', Category::class),
            'eyeshadow' => $this->getReference('category_eyeshadow', Category::class),
        ];

        $productsData = [
            [
                'name' => 'Red Velvet Lipstick',
                'description' => 'A creamy matte lipstick for bold red lips.',
                'price' => 15.99,
                'image' => 'lipstick1.jpg',
                'stock' => 50,
                'category' => 'lipstick',
            ],
            [
                'name' => 'Smooth Matte Foundation',
                'description' => 'Long-lasting foundation for all-day coverage.',
                'price' => 25.00,
                'image' => 'foundation1.jpg',
                'stock' => 40,
                'category' => 'foundation',
            ],
            [
                'name' => 'Nude Eyeshadow Palette',
                'description' => '12-color palette with natural tones.',
                'price' => 18.50,
                'image' => 'eyeshadow1.jpg',
                'stock' => 30,
                'category' => 'eyeshadow',
            ],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            //$product->setImageFile($data['image']);
            $product->setStock($data['stock']);
            $product->setCategory($categories[$data['category']]);
            $product->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
