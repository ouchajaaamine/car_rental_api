<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Entity\User;
use App\State\RegisterProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            processor: RegisterProcessor::class,
            status: 201,
            output: User::class
        )
    ]
)]
final class RegisterDto
{
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Invalid email format')]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(min: 6, minMessage: 'Password must be at least 6 characters')]
    public ?string $password = null;

    #[Assert\NotBlank(message: 'First name is required')]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: 'Last name is required')]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $lastName = null;
}

