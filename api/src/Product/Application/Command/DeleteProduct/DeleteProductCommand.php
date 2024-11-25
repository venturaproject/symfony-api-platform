<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteProductCommand implements CommandInterface
{
    public function __construct(public Uuid $id) { }
}