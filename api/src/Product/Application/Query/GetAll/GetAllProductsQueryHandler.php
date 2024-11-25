<?php

namespace App\Product\Application\Query\GetAll;

use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository) { }

    /**
     * @return ProductDTO[]  - Array de objetos ProductDTO
     */
    public function __invoke(GetAllProductsQuery $query): array
    {
        $products = $this->productRepository->findAll();

        return array_map(function($product) {
            return new ProductDTO(
                (string) $product->getId(),
                $product->getName(),
                $product->getDescription(),
                (float) $product->getPrice(),
                $product->getDateAdd()->format('Y-m-d') 
            );
        }, $products);
    }
}
