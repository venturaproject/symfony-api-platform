<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use ApiPlatform\Metadata\Operation;
use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\DTO\UserDTO;
use App\User\Infrastructure\ValidationDTO\CreateUserInputDTO;

final class CreateUserProcessor implements ProcessorInterface
{
    private CommandBusInterface $commandBus;
    private ValidatorInterface $validator;

    public function __construct(CommandBusInterface $commandBus, ValidatorInterface $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): JsonResponse {
        $inputDTO = new CreateUserInputDTO(
            username: $data->username ?? '',
            email: $data->email ?? '',
            password: $data->password ?? '',
            roles: $data->roles ?? []
        );
    
        // Validar los datos del DTO
        $violations = $this->validator->validate($inputDTO);
    
        if (count($violations) > 0) {
            return $this->formatValidationErrors($violations);
        }
    
        // Crear el comando para la creación del usuario
        $command = new CreateUserCommand(
            username: $inputDTO->username,
            email: $inputDTO->email,
            password: $inputDTO->password,
            roles: $inputDTO->roles
        );
    
        $user = $this->commandBus->execute($command);
    
        $userDTO = new UserDTO($user->getId(), $user->getUsername(), $user->getEmail());
    
        return new JsonResponse([
            'message' => 'User created successfully',
            'user' => $userDTO,  
        ], JsonResponse::HTTP_CREATED);
    }
    


    private function formatValidationErrors(ConstraintViolationListInterface $violations): JsonResponse
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }
}
