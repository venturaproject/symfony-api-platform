<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UserDeletedEvent implements EventInterface
{
    public function __construct(public Uuid $id) { }
}