<?php

namespace App\User\Application\Command\ChangeUsername;

use App\Shared\Application\Command\CommandInterface;

class ChangeUsernameCommand implements CommandInterface
{
    public function __construct(public string $newUsername) { }
}