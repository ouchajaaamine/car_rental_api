<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeInterface;

final class ReservationService
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Checks car availability for given dates.
     */
    public function isCarAvailable(Car $car, DateTimeInterface $start, DateTimeInterface $end): bool
    {
        if ($start > $end) {
            return false;
        }

        $reservedCount = $this->reservationRepository->countActiveReservations($car, $start, $end);
        $availableStock = $car->getInventory() ?? 0;

        return $reservedCount < $availableStock;
    }

    /**
     * Creates a reservation after validation.
     */
    public function createReservation(?User $user, Car $car, DateTimeInterface $start, DateTimeInterface $end, array $data = []): array
    {
        if ($start > $end) {
            return ['success' => false, 'errors' => ['End date must be after start date']];
        }

        if (empty($data['customerPhone']) || empty($data['driverLicenseNumber'])) {
            return ['success' => false, 'errors' => ['Phone and license number are required']];
        }

        if (!$this->isCarAvailable($car, $start, $end)) {
            return ['success' => false, 'errors' => ['Car not available for these dates']];
        }

        $days = (int) $start->diff($end)->days + 1;
        $totalPrice = (string) ($car->getDailyFee() * $days);

        $reservation = new Reservation();
        $reservation->setCar($car);
        $reservation->setUser($user);
        $reservation->setStartDate($start);
        $reservation->setEndDate($end);
        $reservation->setTotalDays($days);
        $reservation->setTotalPrice((string) $totalPrice);
        $reservation->setCustomerName(
            $data['customerName'] ?? 
            ($user ? $user->getFirstName() . ' ' . $user->getLastName() : 'Guest')
        );
        $reservation->setCustomerPhone($data['customerPhone']);
        $reservation->setCustomerEmail($data['customerEmail'] ?? $user?->getEmail());
        $reservation->setDriverLicenseNumber($data['driverLicenseNumber']);
        $reservation->setStatus(ReservationStatus::ACTIVE);
        $reservation->setIsDeleted(false);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return ['success' => true, 'reservation' => $reservation];
    }
}
