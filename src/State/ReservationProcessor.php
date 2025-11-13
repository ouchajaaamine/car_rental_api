<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\CreateReservationDto;
use App\Entity\Car;
use App\Entity\Reservation;
use App\Enum\ReservationStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Processes reservation creation requests.
 */
final class ReservationProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Reservation
    {
        if (!$data instanceof CreateReservationDto) {
            throw new \InvalidArgumentException('Expected CreateReservationDto');
        }

        $car = $this->entityManager->getRepository(Car::class)->find($data->carId);
        if (!$car) {
            throw new NotFoundHttpException('Car not found');
        }

        if ($data->endDate <= $data->startDate) {
            throw new BadRequestHttpException('End date must be after start date');
        }

        $user = $this->security->getUser();
        
        $reservation = new Reservation();
        $reservation->setCar($car);
        $reservation->setStartDate($data->startDate);
        $reservation->setEndDate($data->endDate);
        $reservation->setCustomerName($user->getFirstName() . ' ' . $user->getLastName());
        $reservation->setCustomerPhone($data->customerPhone);
        $reservation->setCustomerEmail($user->getEmail());
        $reservation->setDriverLicenseNumber($data->driverLicenseNumber);
        $reservation->setStatus(ReservationStatus::ACTIVE);
        $reservation->setUser($user);

        $interval = $data->startDate->diff($data->endDate);
        $totalDays = $interval->days;
        $reservation->setTotalDays($totalDays);
        $reservation->setTotalPrice((string)($totalDays * $car->getDailyFee()));

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }
}
