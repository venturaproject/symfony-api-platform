<?php

namespace App\User\Application\Command\DeleteUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Domain\Service\DeleteUserService;

class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DeleteUserService $deleteUserService) { }

    public function __invoke(DeleteUserCommand $command): void
    {
        $this->deleteUserService->delete($command->userId);
    }
}