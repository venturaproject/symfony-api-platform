<?php

namespace App\User\Application\DTO;

use Symfony\Component\Serializer\Annotation\Groups;

class UserDTO
{
    public function __construct(
        #[Groups(['user:read'])]
        private string $id,

        #[Groups(['user:read'])]
        private string $username,

        #[Groups(['user:read'])]
        private string $email
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
