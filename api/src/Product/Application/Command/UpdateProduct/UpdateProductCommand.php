<?php

namespace App\Product\Application\Command\UpdateProduct;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateProductCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public ?string $name = null,
        public ?float $price = null,
        public ?string $description = null,
        public ?\DateTimeInterface $date_add = null
    ) { }
}
