<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationServiceTest extends TestCase
{
    public function testCanCreateUser(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed');
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        
        $service = new RegistrationService($passwordHasher, $entityManager, $validator);
        $result = $service->register([
            'email' => 'test@test.com',
            'password' => 'pass',
            'firstName' => 'amine',
            'lastName' => 'test'
        ]);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('test@test.com', $result['user']->getEmail());
    }
}

