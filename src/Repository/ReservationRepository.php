<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enum\ReservationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeInterface;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Returns reservations based on user role.
     */
    public function findByUserRole(User $user): array
    {
        if ($user->isManager()) {
            return $this->findAll();
        }

        return $this->findBy(['user' => $user]);
    }

    /**
     * Counts active reservations that overlap with given dates.
     */
    public function countActiveReservations(Car $car, DateTimeInterface $start, DateTimeInterface $end): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.car = :car')
            ->andWhere('r.status = :active')
            ->andWhere('r.isDeleted = false')
            ->andWhere('NOT (r.endDate < :start OR r.startDate > :end)')
            ->setParameter('car', $car)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('active', ReservationStatus::ACTIVE->value)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
