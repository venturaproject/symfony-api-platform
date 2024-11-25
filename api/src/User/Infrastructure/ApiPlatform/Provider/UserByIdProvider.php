<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Query\GetCurrentUser\GetCurrentUserQuery;
use App\User\Application\DTO\UserDTO;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;

/**
 * @template-implements ProviderInterface<UserResource>
 */
final class UserByIdProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBusInterface
    ) {
    }

    /**
     * @return UserResource|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserResource
    {
    
        $userDTO = $this->getCurrentUserDTO();

        return $userDTO ? UserResource::fromUserDTO($userDTO) : null;
    }

    /**
     * Gets the UserDTO of the authenticated user.
     *
     * @return UserDTO|null
     */
    private function getCurrentUserDTO(): ?UserDTO
    {
        $query = new GetCurrentUserQuery();
        return $this->queryBusInterface->execute($query);
    }
}

