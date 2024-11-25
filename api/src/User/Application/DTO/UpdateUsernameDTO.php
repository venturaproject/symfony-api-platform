<?php

namespace App\User\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUsernameDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'please enter your username')]
        #[Assert\Length(
            min:3, 
            max: 20, 
            minMessage: 'Your username must have at least 3 characters',
            maxMessage: 'Your username must have at most 20 characters'
        )]
        public string $username
    ){
    }
}