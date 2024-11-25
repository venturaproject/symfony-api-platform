<?php

namespace App\User\Application\Command\ChangePassword;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Service\UserPasswordService;
use App\User\Domain\Entity\User; // Make sure to import User entity
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ChangePasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserPasswordService $userPasswordService,
        private CurrentUserProviderInterface $currentUserProvider
    ) { }

    public function __invoke(ChangePasswordCommand $command): void
    {
        $currentUser = $this->currentUserProvider->getRequiredCurrentUser();

        // Cast to User entity if necessary (assuming AuthUserInterface can be casted to User)
        if (!$currentUser instanceof User) {
            throw new \InvalidArgumentException('Expected an instance of User entity.');
        }

        // Check the current password
        if (!$this->userPasswordService->isPasswordValid($currentUser, $command->currentPassword)) {
            throw new BadCredentialsException('Current password is incorrect.');
        }

        // Change the password if valid
        $this->userPasswordService->changePassword(
            new Uuid($currentUser->getId()), 
            $command->newPassword
        );
    }
}
