<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ValidationDTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserInputDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Please enter your email address')]
        #[Assert\Email(message: 'Please enter a valid email address')]
        public string $email,

        #[Assert\NotBlank(message: 'Please enter your password')]
        #[Assert\Length(
        min: 6,
        minMessage: 'Your password must be at least 6 characters long'
    )]
        public string $password,

        #[Assert\NotBlank(message: 'Please enter your username')]
        #[Assert\Length(
        min: 3,
        minMessage: 'Your username must have at least 3 characters'
    )]
        public string $username,

        #[Assert\NotBlank(message: 'Roles cannot be empty')]
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Choice(choices: ['ROLE_USER', 'ROLE_ADMIN'], message: 'Invalid role.')
        ])]

        /**
         * @var string[] $roles
         */
        public array $roles
    ) {
    }
}


