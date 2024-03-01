<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = (new User)
            ->setLastName('Ansar')
            ->setName('Remy')
            ->setEmail('remy@test.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->hasher->hashPassword(new User, 'Test1234')
            );

        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = (new User)
                ->setLastName('user')
                ->setName($i)
                ->setEmail("user-$i@test.com")
                ->setPassword(
                    $this->hasher->hashPassword($user, 'Test1234')
                );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
