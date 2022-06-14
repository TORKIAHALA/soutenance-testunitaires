<?php

namespace App\DataFixtures;

use App\Entity\BankAccount;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();

        $user->setLastName('Doe');
        $user->setFirstName('John');
        $user->setEmail('john.doe@gmail.com');
        $user->setRoles(['ROLE_USER']);
        // pwd : test
        $user->setPassword('$2y$13$hfqNoOxwWmYHRovjrN11HecsZQ/5C5vFGcRMmQ/krnaO6u591wSDC');

        $bankAccount = new BankAccount();
        $bankAccount->setOwner($user);
        $bankAccount->setBalance(0);
        $bankAccount->setRIB('FR1234567890123');

        $manager->persist($user);
        $manager->persist($bankAccount);
        $manager->flush();
    }
}
