<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\RegisterDto;
use App\Entity\User;
use App\Service\RegistrationService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Processes user registration requests.
 */
final class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private RegistrationService $registrationService
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if (!$data instanceof RegisterDto) {
            throw new \InvalidArgumentException('Expected RegisterDto');
        }

        $result = $this->registrationService->register([
            'email' => $data->email,
            'password' => $data->password,
            'firstName' => $data->firstName,
            'lastName' => $data->lastName,
        ]);

        if (!$result['success']) {
            $errorMessage = implode(', ', $result['errors'] ?? ['Registration failed']);
            throw new BadRequestHttpException($errorMessage);
        }

        return $result['user'];
    }
}
