<?php

declare(strict_types=1);

namespace App\Product\Application\Exception;

use RuntimeException;

class ProductNotFoundException extends RuntimeException
{
    private string $productId;

    public function __construct(string $productId)
    {
        parent::__construct(sprintf('Product with ID "%s" not found.', $productId));
        $this->productId = $productId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
