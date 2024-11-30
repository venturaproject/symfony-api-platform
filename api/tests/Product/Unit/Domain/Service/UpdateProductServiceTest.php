<?php

declare(strict_types=1);

namespace Tests\Product\Unit\Domain\Service;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Service\UpdateProductService;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UpdateProductServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private UpdateProductService $updateProductService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->updateProductService = new UpdateProductService($this->productRepository);
    }

    public function testUpdateProductSuccess(): void
    {
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
        $product = new Product($uuid, 'Old Name', 100.0);

        $this->productRepository
            ->method('findById')
            ->with($uuid)
            ->willReturn($product);

        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->with($product);

        $updatedProduct = $this->updateProductService->update(
            $uuid,
            'New Name',
            150.0,
            null,
            null
        );

        $this->assertEquals('New Name', $updatedProduct->getName());
        $this->assertEquals(150.0, $updatedProduct->getPrice());
    }

    public function testUpdateProductNotFoundThrowsException(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionMessage('Product with ID "123e4567-e89b-12d3-a456-426614174000" not found.');
    
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
    
        $this->productRepository
            ->method('findById')
            ->with($uuid)
            ->willReturn(null);
    
        $this->updateProductService->update($uuid, 'New Name', 150.0, null, null);
    }
    
}
