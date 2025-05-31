<?php
namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use function PHPUnit\Framework\assertFileNotEquals;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements FixtureGroupInterface
{   private $passwordEncoder;
    /**
     * UserFixtures constructor
    */
    public function __construct(UserPasswordHasherInterface $encoder)
    { $this->passwordEncoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {   $user1=new User();
        $user1->setEmail('aziza123@gmail.com');
        $user1->setPassword($this->passwordEncoder->hashPassword($user1,'123'));
        $this->addReference('user_aziza123@gmail.com', $user1);
        $userAdmin=new User();
        $userAdmin->setEmail('admin123@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setIsVerified(true);
        $userAdmin->setPassword($this->passwordEncoder->hashPassword($userAdmin,'456'));
        $manager->persist($user1);
        $manager->persist($userAdmin);


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['groupUser'];
    }


}
