<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\Query\GetById\GetIdProductQuery;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Infrastructure\ApiPlatform\Resource\ProductResource;

/**
 * @template-implements ProviderInterface<ProductResource>
 */
final class ProductByIdProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBusInterface
    ) {
    }

    /**
     * @return ProductResource|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?ProductResource
    {
      
        $productId = $uriVariables['id'];

        $productDTO = $this->getProductDTO($productId);

        if (!$productDTO) {
            throw new ProductNotFoundException($productId);
        }

        return ProductResource::fromProductDTO($productDTO);
    }

    /**
     * @return ProductDTO|null
     */
    private function getProductDTO(string $productId): ?ProductDTO
    {
        $query = new GetIdProductQuery($productId);
        return $this->queryBusInterface->execute($query);
    }
}
