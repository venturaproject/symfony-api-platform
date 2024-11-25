<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\OpenApi\Model\Operation;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Infrastructure\ApiPlatform\Processor\CreateProductProcessor;
use App\Product\Infrastructure\ApiPlatform\Processor\UpdateProductProcessor;
use App\Product\Infrastructure\ApiPlatform\Processor\DeleteProductProcessor;
use App\Product\Infrastructure\ApiPlatform\Provider\ProductByIdProvider;
use App\Product\Infrastructure\ApiPlatform\Provider\ProductsProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Product',
    operations: [
        new GetCollection(
            openapi: new Operation(
                summary: 'Search products',
                description: 'Fetch a collection of products.'
            ),
            provider: ProductsProvider::class,
        ),
        new Get(
            openapi: new Operation(
                summary: 'Get product',
                description: 'Fetch a specific product by ID.'
            ),
            provider: ProductByIdProvider::class,
        ),
        new Post(
            openapi: new Operation(
                summary: 'Create product',
                description: 'Create a new product.'
            ),
            denormalizationContext: ['groups' => ['create']],
            validationContext: ['groups' => ['create']],
            processor: CreateProductProcessor::class,
        ),
        new Put(
            openapi: new Operation(
                summary: 'Update product',
                description: 'Update an existing product by ID.'
            ),
            denormalizationContext: ['groups' => ['update']],
            validationContext: ['groups' => ['update']],
            processor: UpdateProductProcessor::class,
            provider: ProductByIdProvider::class,
        ),
        new Patch(
            openapi: new Operation(
                summary: 'Partially update product',
                description: 'Partially update an existing product by ID.'
            ),
            denormalizationContext: ['groups' => ['update']],
            validationContext: ['groups' => ['update']],
            processor: UpdateProductProcessor::class,
            provider: ProductByIdProvider::class,
        ),
        new Delete(
            openapi: new Operation(
                summary: 'Delete product',
                description: 'Delete an existing product by ID.'
            ),
            processor: DeleteProductProcessor::class,
            provider: ProductByIdProvider::class,
        ),
    ],
)]
final class ProductResource
{
    #[ApiProperty(readable: true, writable: false, identifier: true)]
    #[Groups(groups: ['read'])]
    public ?string $id = null;

    #[Assert\Length(min: 1, max: 255)]
    #[Assert\NotNull(groups: ['create', 'update'])]
    #[Groups(groups: ['read', 'create', 'update'])]
    public string $name;

    #[Assert\NotNull(groups: ['create', 'update'])]
    #[Groups(groups: ['read', 'create', 'update'])]
    public float $price;

    #[Assert\Length(min: 1, max: 255)]
    #[Groups(groups: ['read', 'create', 'update'])]
    public ?string $description = null;

    #[Groups(groups: ['read', 'create', 'update'])]
    public ?string $date_add = null;

    public function __construct() {}

    public static function fromProductDTO(ProductDTO $productDTO): ProductResource
    {
        $instance = new self();
        $instance->id = $productDTO->id;
        $instance->name = $productDTO->name;
        $instance->price = $productDTO->price;
        $instance->description = $productDTO->description;
        $instance->date_add = $productDTO->date_add;
        
        return $instance;
    }
}
