<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetCurrentUser;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\User\Application\DTO\UserDTO;
use App\User\Domain\Exception\UserNotFoundException;

class GetCurrentUserQueryHandler implements QueryHandlerInterface
{
    private CurrentUserProviderInterface $currentUserProvider;

    public function __construct(CurrentUserProviderInterface $currentUserProvider)
    {
        $this->currentUserProvider = $currentUserProvider;
    }

    public function __invoke(GetCurrentUserQuery $query): UserDTO
    {
        $user = $this->currentUserProvider->getRequiredCurrentUser();
    
        return new UserDTO($user->getId(), $user->getUsername(), $user->getEmail());
    }
}