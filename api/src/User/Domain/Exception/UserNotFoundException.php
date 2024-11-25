<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class UserNotFoundException extends NotFoundException
{
    public function __construct(string $message = 'User not found')
    {
        parent::__construct($message, 404);
    }
}