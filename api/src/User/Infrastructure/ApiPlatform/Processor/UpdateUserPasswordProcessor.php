<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use App\User\Application\Command\ChangePassword\ChangePasswordCommand;
use App\User\Application\DTO\UpdatePasswordDTO;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private CommandBusInterface $commandBus,
        private TokenStorageInterface $tokenStorage
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Obtener el token del usuario
        $token = $this->tokenStorage->getToken();
        if (!$token || !$token->getUser()) {
            throw new BadRequestHttpException('JWT Token not found or invalid');
        }

        $user = $token->getUser();

        // Mapear el DTO con los datos de entrada
        $data = new UpdatePasswordDTO(
            current_password: $data->current_password ?? '',
            new_password: $data->new_password ?? '',
            new_password_confirmation: $data->new_password_confirmation ?? ''
        );

        // Validar el DTO
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            throw new ValidationFailedException($data, $errors);
        }

        // Ejecutar el comando para cambiar la contraseña
        $this->commandBus->execute(new ChangePasswordCommand(
            currentPassword: $data->current_password,
            newPassword: $data->new_password
        ));

        // Crear una respuesta de éxito que será manejada por el proveedor de respuestas de API Platform
        return new JsonResponse(
            ['message' => 'Password updated successfully'],
            Response::HTTP_OK
        );
    }
}





