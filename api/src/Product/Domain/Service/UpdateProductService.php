<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository) { }

    public function update(Uuid $id, ?string $name, ?float $price, ?string $description, ?\DateTimeInterface $date_add): Product
    {
        $product = $this->productRepository->findById($id);

        if ($product === null) {
            throw new ProductNotFoundException($id->toString());
        }

        // Actualizamos los valores del producto
        $product->setName($name ?? $product->getName());
        $product->setPrice($price ?? $product->getPrice());
        $product->setDescription($description ?? $product->getDescription());

        if ($date_add !== null) {
            $product->setDateAdd($date_add);
        }


        $this->productRepository->save($product); 

        return $product;
    }
}
