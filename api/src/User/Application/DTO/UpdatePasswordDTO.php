<?php

namespace App\User\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdatePasswordDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Please enter your current password')]
        public string $current_password,

        #[Assert\NotBlank(message: 'Please enter your new password')]
        #[Assert\Length(
            min: 6,
            minMessage: 'Your new password must be at least 6 characters long'
        )]
        public string $new_password,

        #[Assert\NotBlank(message: 'Please confirm your new password')]
        #[Assert\EqualTo(
            propertyPath: 'new_password',
            message: 'The new password confirmation does not match'
        )]
        public string $new_password_confirmation
    ) {}
}

