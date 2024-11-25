<?php

namespace App\User\Application\Query\GetUserById;

use App\Shared\Application\Query\QueryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetUserByIdQuery implements QueryInterface
{
    public function __construct(public Uuid $id) { }
}