<?php

namespace App\Tests\Service;

use App\Entity\Car;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    public function testCarIsAvailable(): void
    {
        $repository = $this->createMock(ReservationRepository::class);
        $repository->method('countActiveReservations')->willReturn(2);
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $service = new ReservationService($repository, $entityManager);
        
        $car = new Car();
        $car->setInventory(5);
        
        $start = new \DateTime('2025-11-01');
        $end = new \DateTime('2025-12-01');
        
        $available = $service->isCarAvailable($car, $start, $end);
        
        $this->assertTrue($available);
    }
    
    public function testCarNotAvailable(): void
    {
        $repository = $this->createMock(ReservationRepository::class);
        $repository->method('countActiveReservations')->willReturn(5);
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $service = new ReservationService($repository, $entityManager);
        
        $car = new Car();
        $car->setInventory(5);
        
        $start = new \DateTime('2025-11-01');
        $end = new \DateTime('2025-12-01');
        
        $available = $service->isCarAvailable($car, $start, $end);
        
        $this->assertFalse($available);
    }
}

