<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\User\Application\DTO\UserDTO;
use App\User\Infrastructure\ApiPlatform\Provider\UserByIdProvider;
use App\User\Infrastructure\ApiPlatform\Processor\CreateUserProcessor;
use App\User\Infrastructure\ApiPlatform\Processor\UpdateUserPasswordProcessor;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Get(
            uriTemplate: '/users/current',
            openapi: new Operation(
                summary: 'Get current user',
                description: 'Fetch the currently authenticated user.'
            ),
            provider: UserByIdProvider::class,
            output: UserDTO::class,
        ),
        new Post(
            uriTemplate: '/users',
            openapi: new Operation(
                summary: 'Create a new user',
                description: 'Creates a new user with the given details.'
            ),
            processor: CreateUserProcessor::class,

        ),

    ],
)]
final class UserResource
{
    #[ApiProperty(readable: true, writable: false, identifier: true)]
    #[Groups(groups: ['user:read'])]
    public ?string $id = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Groups(groups: ['user:read', 'user:write'])]
    public ?string $username = null;

    #[ApiProperty(readable: true, writable: true)]
    #[Groups(groups: ['user:read', 'user:write'])]
    public ?string $email = null;

    #[ApiProperty(readable: false, writable: true)]
    #[Groups(groups: ['user:write'])]
    public ?string $password = null;

    #[ApiProperty(readable: false, writable: true)]
    #[Groups(groups: ['user:write'])]
    /**
     * @var array<int, string>|null
     */
    public ?array $roles = null;

    public static function fromUserDTO(UserDTO $userDTO): UserResource
    {
        $instance = new self();
        $instance->id = $userDTO->getId();
        $instance->username = $userDTO->getUsername();
        $instance->email = $userDTO->getEmail();

        return $instance;
    }
}
