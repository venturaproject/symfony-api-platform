<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\User\Domain\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * This class is responsible for retrieving the current user from the Symfony security component.
 * It provides methods to retrieve the current user, or null if the user is not logged in.
 */
readonly class CurrentUserProvider implements CurrentUserProviderInterface
{
    public function __construct(private Security $security) {}

    /**
     * Returns the current user or throws an exception if the user is not logged in.
     * 
     * @throws AccessDeniedException
     * @return User - The current user
     */
    public function getRequiredCurrentUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('Access Denied');
        }

        return $user;
    }

    /**
     * Returns the current user or null if the user is not logged in.
     * 
     * @return User|null - The current user or null if the user is not logged in
     */
    public function getNullableCurrentUser(): ?User
    {
        $user = $this->security->getUser();

        return $user instanceof User ? $user : null;
    }
}
