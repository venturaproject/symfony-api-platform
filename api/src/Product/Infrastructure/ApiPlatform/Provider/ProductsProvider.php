<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\Query\GetAll\GetAllProductsQuery;
use App\Product\Infrastructure\ApiPlatform\Resource\ProductResource;

/**
 * @template-implements ProviderInterface<ProductResource>
 */
final class ProductsProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBusInterface,
        private readonly Pagination $pagination
    ) {
    }

    /**
     * @return ProductResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $page = $this->pagination->getPage($context);
        $itemsPerPage = $this->pagination->getLimit($operation, $context);

        $products = $this->getProductsDTOs($page, $itemsPerPage);

        return $this->mapProductDTOsToProductResources($products);
    }

    /**
     * @return ProductDTO[]
     */
    private function getProductsDTOs(int $page, int $itemsPerPage): array
    {
        $query = new GetAllProductsQuery(); 
        return $this->queryBusInterface->execute($query);
    }

    /**
     * @param ProductDTO[] $productDTOs
     * @return ProductResource[]
     */
    private function mapProductDTOsToProductResources(array $productDTOs): array
    {
        $resources = [];
        foreach ($productDTOs as $productDTO) {
            $resources[] = ProductResource::fromProductDTO($productDTO);
        }

        return $resources;
    }
}
