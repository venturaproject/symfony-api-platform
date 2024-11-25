<?php
namespace App\User\Application\Command\ChangeUsername;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\User\Domain\Service\UserProfileService;
use App\Shared\Domain\ValueObject\Uuid;

class ChangeUsernameCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserProfileService $userProfileService,
        private CurrentUserProviderInterface $currentUserProvider
    ) { }

    public function __invoke(ChangeUsernameCommand $command): void
    {
        $currentUser = $this->currentUserProvider->getRequiredCurrentUser();

        $this->userProfileService->changeUsername(
            new Uuid($currentUser->getId()),
            $command->newUsername
        );
    }
}