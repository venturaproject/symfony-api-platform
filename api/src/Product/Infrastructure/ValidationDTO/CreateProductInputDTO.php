<?php

namespace App\Product\Infrastructure\ValidationDTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductInputDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'The product name cannot be blank.')]
        #[Assert\Length(
            min: 3, minMessage: 'The product name must be at least 3 characters long.',
            max: 255, maxMessage: 'The product name cannot be longer than 255 characters.'
        )]
        public string $name,

        #[Assert\NotBlank(message: 'The product price cannot be blank.')]
        #[Assert\Positive(message: 'The product price must be a positive number.')]
        public float $price,

        #[Assert\Length(max: 1000, maxMessage: 'The description cannot be longer than 1000 characters.')]
        public ?string $description = null,

        #[Assert\NotBlank(message: 'The date_add cannot be blank.')]
        #[Assert\Regex(
            pattern: "/^\d{4}-\d{2}-\d{2}$/",
            message: 'The date_add must be in the format YYYY-MM-DD.'
        )]
        public ?string $date_add = null
    ) { }
}



