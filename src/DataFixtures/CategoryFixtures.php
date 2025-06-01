<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Example categories
        $categories = [
            ['name' => 'Lipstick', 'description' => 'Various shades of lipstick.'],
            ['name' => 'Foundation', 'description' => 'Different types of foundation for all skin tones.'],
            ['name' => 'Skincare', 'description' => 'Products for facial and body care.'],
            ['name' => 'Eyeshadow', 'description' => 'Eye makeup products with multiple colors.'],
            ['name' => 'Blush', 'description' => 'Add color to your cheeks.'],
        ];

        foreach ($categories as $index => $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setDescription($data['description']);

            // Save reference for later use (e.g. in ProductFixtures)
            $this->addReference('category_' . strtolower($data['name']), $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}

