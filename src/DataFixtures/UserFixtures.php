<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create manager user (admin)
        $manager_user = new User();
        $manager_user->setEmail('admin@carrental.com');
        $manager_user->setFirstName('Hassan');
        $manager_user->setLastName('Bennani');
        $manager_user->setRoles([UserRole::MANAGER->value]);
        
        $hashedPassword = $this->passwordHasher->hashPassword($manager_user, 'admin123');
        $manager_user->setPassword($hashedPassword);
        
        $manager->persist($manager_user);
        $this->addReference('manager-user', $manager_user);

        // Create customer 1
        $customer1 = new User();
        $customer1->setEmail('karim.alaoui@gmail.com');
        $customer1->setFirstName('Karim');
        $customer1->setLastName('Alaoui');
        $customer1->setRoles([UserRole::CUSTOMER->value]);
        
        $hashedPassword = $this->passwordHasher->hashPassword($customer1, 'karim123');
        $customer1->setPassword($hashedPassword);
        
        $manager->persist($customer1);
        $this->addReference('customer1-user', $customer1);

        // Create customer 2
        $customer2 = new User();
        $customer2->setEmail('fatima.idrissi@gmail.com');
        $customer2->setFirstName('Fatima');
        $customer2->setLastName('Idrissi');
        $customer2->setRoles([UserRole::CUSTOMER->value]);
        
        $hashedPassword = $this->passwordHasher->hashPassword($customer2, 'fatima123');
        $customer2->setPassword($hashedPassword);
        
        $manager->persist($customer2);
        $this->addReference('customer2-user', $customer2);

        $manager->flush();
    }
}
