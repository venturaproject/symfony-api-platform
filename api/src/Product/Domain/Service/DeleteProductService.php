<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\Exception\ForbidenException;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) { }

    public function delete(Uuid $id): void
    {
        $product = $this->productRepository->findById($id);

        if ($product === null) {
            throw new ProductNotFoundException($id->toString());
        }

        $this->productRepository->remove($product);
    }
}



