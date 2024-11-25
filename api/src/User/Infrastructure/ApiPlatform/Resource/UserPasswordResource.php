<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\User\Infrastructure\ApiPlatform\Processor\UpdateUserPasswordProcessor;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Schema;
use ArrayObject;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'UserPassword',
    operations: [
        new Put(
            uriTemplate: '/password/change-password',
            openapi: new Operation(
                summary: 'Change user password',
                description: 'Allows the current user to update their password.'
            ),
            processor: UpdateUserPasswordProcessor::class, 
        ),
    ],
)]
final class UserPasswordResource
{
    #[ApiProperty(readable: false, writable: true)]
    #[Groups(groups: ['user:write'])]
    public ?string $current_password = null;

    #[ApiProperty(readable: false, writable: true)]
    #[Groups(groups: ['user:write'])]
    public ?string $new_password = null;

    #[ApiProperty(readable: false, writable: true)]
    #[Groups(groups: ['user:write'])]
    public ?string $new_password_confirmation = null;

    public function __construct(?string $current_password = null, ?string $new_password = null, ?string $new_password_confirmation = null)
    {
        $this->current_password = $current_password;
        $this->new_password = $new_password;
        $this->new_password_confirmation = $new_password_confirmation;
    }
}
