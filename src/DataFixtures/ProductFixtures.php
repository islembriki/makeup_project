<?php

namespace App\DataFixtures;


use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use DateTimeImmutable;
use phpDocumentor\Reflection\DocBlock\Tags\Implements_;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends Fixture implements DependentFixtureInterface,FixtureGroupInterface
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
                'image' => 'OIF.jpg',
                'stock' => 50,
                'category' => 'lipstick',
            ],
            [
                'name' => 'Smooth Matte Foundation',
                'description' => 'Long-lasting foundation for all-day coverage.',
                'price' => 25.00,
                'image' => 'OIP.jpg',
                'stock' => 40,
                'category' => 'foundation',
            ],
            [
                'name' => 'Nude Eyeshadow Palette',
                'description' => '12-color palette with natural tones.',
                'price' => 18.50,
                'image' => 'oppp.jpg',
                'stock' => 30,
                'category' => 'eyeshadow',
            ],
        ];

        foreach ($productsData as $index => $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $imagePath = __DIR__.'/../../public/images/products/'.$data['image'];
            $product->setImageFile(new File($imagePath)); // This triggers upload
            $product->setImage($data['image']); // manually set the filename
            // 'image' field will be auto-set by VichUploader after flush
            $product->setStock($data['stock']);
            $product->setCategory($categories[$data['category']]);
            //$product->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($product);
            $this->addReference('product_' . $index, $product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
    public static function getGroups(): array
    {
        return ['product'];
    }
}
