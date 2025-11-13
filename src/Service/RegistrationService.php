<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Handles user registration.
 */
class RegistrationService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * Creates a new user account.
     */
    public function register(array $data): array
    {
        $required = ['email', 'password', 'firstName', 'lastName'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'errors' => ['message' => "Missing field: {$field}"]];
            }
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setRoleCustomer();
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return ['success' => false, 'errors' => $errorMessages];
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ['success' => true, 'user' => $user];
    }
}
