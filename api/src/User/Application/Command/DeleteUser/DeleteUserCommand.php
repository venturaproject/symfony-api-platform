<?php

namespace App\User\Application\Command\DeleteUser;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteUserCommand implements CommandInterface
{
    public function __construct(public Uuid $userId) { }
}