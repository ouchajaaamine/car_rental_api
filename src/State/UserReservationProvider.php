<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ReservationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserReservationProvider implements ProviderInterface
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly Security $security
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $userId = $uriVariables['id'];
        $currentUser = $this->security->getUser();
        
        if (!$this->security->isGranted('ROLE_MANAGER')) {
            return [];
        }

        return $this->reservationRepository->findBy(['user' => $userId]);
    }
}
