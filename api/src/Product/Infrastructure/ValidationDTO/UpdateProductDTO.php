<?php

namespace App\Product\Infrastructure\ValidationDTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductDTO
{
    public function __construct(
        #[Assert\Length(min: 3, minMessage: 'Product name must be at least 3 characters long.', max: 255, maxMessage: 'Product name cannot be longer than 255 characters.')]
        public ?string $name = null,

        #[Assert\Positive(message: 'The product price must be a positive number.')]
        public ?float $price = null,

        #[Assert\Length(max: 1000, maxMessage: 'The description cannot be longer than 1000 characters.')]
        public ?string $description = null,

        // También hacemos que date_add sea opcional
        public ?\DateTimeInterface $date_add = null,
    ) { }
}
