<?php

namespace App\User\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandInterface;

class CreateUserCommand implements CommandInterface
{
    /**
     * @param string[] $roles Array of roles.
     */
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public array $roles = []
    ) { }
}
