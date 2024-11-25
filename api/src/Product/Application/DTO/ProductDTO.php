<?php

namespace App\Product\Application\DTO;

readonly class ProductDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public float $price,
        public string $date_add
    ) { }
}
