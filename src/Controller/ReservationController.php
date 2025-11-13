<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations', name: 'api_reservations_list', methods: ['GET'])]
    #[IsGranted('ROLE_CUSTOMER')]
    public function list(ReservationRepository $reservationRepository): JsonResponse
    {
        $reservations = $reservationRepository->findByUserRole($this->getUser());
        
        return $this->json($reservations, 200, [], ['groups' => ['reservation:read']]);
    }
}
