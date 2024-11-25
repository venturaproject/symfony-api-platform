<?php

namespace App\Product\Application\Command\CreateProduct;

use App\Shared\Application\Command\CommandInterface;

class CreateProductCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public float $price,
        public ?string $description = null,
        public ?\DateTimeInterface $date_add = null
    ) { }
}

