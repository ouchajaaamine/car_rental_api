<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actualReturnDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $customerName = null;

    #[ORM\Column(length: 20)]
    private ?string $customerPhone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $customerEmail = null;

    #[ORM\Column(length: 50)]
    private ?string $driverLicenseNumber = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $dailyRate = null;

    #[ORM\Column]
    private ?int $totalDays = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $lateFee = null;

    #[ORM\Column(type: 'string', enumType: ReservationStatus::class)]
    private ReservationStatus $status = ReservationStatus::ACTIVE;

    #[ORM\Column]
    private bool $isDeleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getActualReturnDate(): ?\DateTimeInterface
    {
        return $this->actualReturnDate;
    }

    public function setActualReturnDate(?\DateTimeInterface $actualReturnDate): static
    {
        $this->actualReturnDate = $actualReturnDate;
        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(string $customerPhone): static
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function getDriverLicenseNumber(): ?string
    {
        return $this->driverLicenseNumber;
    }

    public function setDriverLicenseNumber(string $driverLicenseNumber): static
    {
        $this->driverLicenseNumber = $driverLicenseNumber;
        return $this;
    }

    public function getDailyRate(): ?string
    {
        return $this->dailyRate;
    }

    public function setDailyRate(string $dailyRate): static
    {
        $this->dailyRate = $dailyRate;
        return $this;
    }

    public function getTotalDays(): ?int
    {
        return $this->totalDays;
    }

    public function setTotalDays(int $totalDays): static
    {
        $this->totalDays = $totalDays;
        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    public function getLateFee(): ?string
    {
        return $this->lateFee;
    }

    public function setLateFee(?string $lateFee): static
    {
        $this->lateFee = $lateFee;
        return $this;
    }

    public function getStatus(): ReservationStatus
    {
        return $this->status;
    }

    public function setStatus(ReservationStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
