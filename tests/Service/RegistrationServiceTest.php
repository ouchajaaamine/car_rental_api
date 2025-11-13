<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Tests for user registration functionality.
 */
class RegistrationServiceTest extends TestCase
{
    /**
     * Test that we can successfully create a new user with basic info.
     * We mock the password hasher and validator to avoid database dependencies.
     */
    public function testCanCreateUser(): void
    {
        // Mock password hasher to return a simple string
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed');
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        // Mock validator to return no errors
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        
        $service = new RegistrationService($passwordHasher, $entityManager, $validator);
        $result = $service->register([
            'email' => 'test@test.com',
            'password' => 'pass',
            'firstName' => 'amine',
            'lastName' => 'test'
        ]);
        
        // Check that user was created with correct email
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('test@test.com', $result['user']->getEmail());
    }
}
