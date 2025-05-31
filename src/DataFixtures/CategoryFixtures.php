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
/**
 * 🔁 What addReference() Does in Fixtures
 * The method addReference(string $name, object $object) is provided by Doctrine’s fixtures system to store an object (like an entity) with a unique name, so you can retrieve it later in another fixture using getReference().
 *
 * ✅ Why use it?
 * Let’s say you're creating Category entities in CategoryFixtures, and then in ProductFixtures you want to assign a product to a specific category.
 *
 * You can’t directly query the database in fixtures (Doctrine says: fixtures should only use in-memory logic), so instead, you use:
 *
 * In CategoryFixtures:
 * php
 * Copier
 * Modifier
 * $this->addReference('category_lipstick', $lipstickCategory);
 * This stores the $lipstickCategory object under the name 'category_lipstick'.
 *
 * In ProductFixtures (after declaring implements DependentFixtureInterface):
 * php
 * Copier
 * Modifier
 * $lipstickCategory = $this->getReference('category_lipstick');
 * $product->setCategory($lipstickCategory);
 * Now, the product is linked to the correct category — without querying the DB, just by referencing what's already loaded in memory.
 *
 * 🧠 Summary
 * Method    Purpose
 * addReference    Save an entity under a name in one fixture
 * getReference    Retrieve that entity by name in another fixture
 *
 * ✅ This is how you link data across fixtures.
 *
 * Let me know if you want to see it in full context inside ProductFixtures.
 */
