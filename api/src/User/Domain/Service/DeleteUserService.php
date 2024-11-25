<?php

namespace App\User\Domain\Service;

use App\Shared\Domain\Event\DomainEventBusInterface;
use App\Shared\Domain\Exception\ForbidenException;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Security\CurrentUserProvider;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

class DeleteUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DomainEventBusInterface $domainEventBus,
        private CurrentUserProvider $currentUserProvider
    ) { }

    /**
     * Delete a user.
     * 
     * @param Uuid $id - The user ID
     * @return void
     */
    public function delete(Uuid $id): void
    {
        $currentUser = $this->currentUserProvider->getRequiredCurrentUser();
        
        if (in_array('ROLE_ADMIN', $currentUser->getRoles()) === false) {
            throw new ForbidenException();
        }
        
        $user = $this->userRepository->findById($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $this->userRepository->remove($user);

        $events = $user->releaseEvents();

        $this->domainEventBus->dispatch(...$events);
    }
}
