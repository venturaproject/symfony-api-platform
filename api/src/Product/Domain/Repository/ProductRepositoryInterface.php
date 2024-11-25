<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function remove(Product $product): void;

    /**
     * @return Product|null - Devuelve un objeto Product o null
     */
    public function findById(Uuid $id): ?Product;

    /**
     * @return Product[] - Array de objetos Product
     */
    public function findAll(): array; 
}
