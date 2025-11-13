<?php

namespace App\Tests\Service;

use App\Entity\Car;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for car availability checking.
 */
class ReservationServiceTest extends TestCase
{
    /**
     * Check that a car is available when there are units left.
     * We simulate 5 cars with only 2 active reservations.
     */
    public function testCarIsAvailable(): void
    {
        // Mock repository returning 2 active reservations
        $repository = $this->createMock(ReservationRepository::class);
        $repository->method('countActiveReservations')->willReturn(2);
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $service = new ReservationService($repository, $entityManager);
        
        // Car with 5 available units
        $car = new Car();
        $car->setInventory(5);
        
        $start = new \DateTime('2025-11-01');
        $end = new \DateTime('2025-12-01');
        
        $available = $service->isCarAvailable($car, $start, $end);
        
        // 5 cars - 2 reserved = 3 available, should be TRUE
        $this->assertTrue($available);
    }
    
    /**
     * Check that a car is not available when everything is booked.
     * We simulate 5 cars with already 5 active reservations.
     */
    public function testCarNotAvailable(): void
    {
        // Mock repository returning 5 active reservations
        $repository = $this->createMock(ReservationRepository::class);
        $repository->method('countActiveReservations')->willReturn(5);
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $service = new ReservationService($repository, $entityManager);
        
        // Car with 5 available units
        $car = new Car();
        $car->setInventory(5);
        
        $start = new \DateTime('2025-11-01');
        $end = new \DateTime('2025-12-01');
        
        $available = $service->isCarAvailable($car, $start, $end);
        
        // 5 cars - 5 reserved = 0 available, should be FALSE
        $this->assertFalse($available);
    }
}

