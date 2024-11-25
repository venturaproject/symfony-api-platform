<?php

namespace App\Product\Application\Query\GetById;

use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetIdProductQueryHandler implements QueryHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(GetIdProductQuery $query): ?ProductDTO
    {
        // Busca el producto por su ID
        $product = $this->productRepository->findById($query->getProductId());

        // Si el producto no existe, devuelve null
        if (!$product) {
            return null;
        }

        // Convierte el producto en un DTO para evitar exponer la entidad directamente
        return new ProductDTO(
            $product->getId(), // Aquí pasas el objeto Uuid
            $product->getName(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getDateAdd()->format('Y-m-d'),
        );
    }
}