<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\Shared\Domain\Security\UserPasswordHasherInterface;
use App\Shared\Domain\UuidGenerator\UuidGeneratorInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;

class UserFactory 
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private UserPasswordHasherInterface $userPasswordHasher
    ){   
    }

    /**
     * @param string[] $roles
     */
    public function create(string $userName, string $email, string $password, array $roles = ['ROLE_USER']): User
    {
        $user = new User(
            new Uuid($this->uuidGenerator->generate()),
            $userName,
            $email,
            $password
        );
    
        $user->setPassword($this->userPasswordHasher->hash($user, $password));
        $user->setRoles($roles);
    
        return $user;
    }
}
