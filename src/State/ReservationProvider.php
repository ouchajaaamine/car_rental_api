<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ReservationRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Filters reservations based on user role.
 */
final class ReservationProvider implements ProviderInterface
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly Security $security
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return [];
        }

        return $this->reservationRepository->findByUserRole($user);
    }
}
