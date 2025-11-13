<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\FuelType;
use App\Enum\TransmissionType;
use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Car entity.
 */
#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_CUSTOMER')"),
        new Get(security: "is_granted('ROLE_CUSTOMER')"),
        new Post(security: "is_granted('ROLE_MANAGER')"),
        new Patch(security: "is_granted('ROLE_MANAGER')"),
        new Delete(security: "is_granted('ROLE_MANAGER')")
    ],
    normalizationContext: ['groups' => ['car:read']],
    denormalizationContext: ['groups' => ['car:write']]
)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['car:read', 'car:write', 'reservation:read'])]
    private ?string $model = null;

    #[ORM\Column(length: 100)]
    #[Groups(['car:read', 'car:write', 'reservation:read'])]
    private ?string $brand = null;

    #[ORM\Column]
    #[Groups(['car:read', 'car:write'])]
    private ?int $inventory = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    #[Groups(['car:read', 'car:write', 'reservation:read'])]
    private ?string $dailyFee = null;

    #[ORM\Column]
    #[Groups(['car:read', 'car:write'])]
    private ?int $seats = null;

    #[ORM\Column(type: 'string', enumType: TransmissionType::class)]
    #[Groups(['car:read', 'car:write'])]
    private TransmissionType $transmission = TransmissionType::AUTOMATIC;

    #[ORM\Column(type: 'string', enumType: FuelType::class)]
    #[Groups(['car:read', 'car:write'])]
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
