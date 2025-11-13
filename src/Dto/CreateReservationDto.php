<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\ReservationProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/reservations',
            processor: ReservationProcessor::class,
            security: "is_granted('ROLE_CUSTOMER')"
        )
    ]
)]
final class CreateReservationDto
{
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $startDate = null;

    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $endDate = null;

    #[Assert\NotBlank]
    public ?int $carId = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 20)]
    public ?string $customerPhone = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 50)]
    public ?string $driverLicenseNumber = null;
}
