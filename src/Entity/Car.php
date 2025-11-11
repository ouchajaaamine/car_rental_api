<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\FuelType;
use App\Enum\TransmissionType;
use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $model = null;

    #[ORM\Column(length: 100)]
    private ?string $brand = null;

    #[ORM\Column]
    private ?int $inventory = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $dailyFee = null;

    #[ORM\Column]
    private ?int $seats = null;

    #[ORM\Column(type: 'string', enumType: TransmissionType::class)]
    private TransmissionType $transmission = TransmissionType::AUTOMATIC;

    #[ORM\Column(type: 'string', enumType: FuelType::class)]
    private FuelType $fuelType = FuelType::GASOLINE;

    #[ORM\Column]
    private bool $isDeleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(int $inventory): static
    {
        $this->inventory = $inventory;
        return $this;
    }

    public function getDailyFee(): ?string
    {
        return $this->dailyFee;
    }

    public function setDailyFee(string $dailyFee): static
    {
        $this->dailyFee = $dailyFee;
        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;
        return $this;
    }

    public function getTransmission(): TransmissionType
    {
        return $this->transmission;
    }

    public function setTransmission(TransmissionType $transmission): static
    {
        $this->transmission = $transmission;
        return $this;
    }

    public function getFuelType(): FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(FuelType $fuelType): static
    {
        $this->fuelType = $fuelType;
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
