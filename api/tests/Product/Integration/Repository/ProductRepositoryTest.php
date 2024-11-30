<?php

declare(strict_types=1);

namespace Tests\Product\Integration\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Infrastructure\Repository\ProductRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    private ProductRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel(); 
        $this->repository = self::getContainer()->get(ProductRepository::class);  // Obtiene el servicio desde el contenedor
    }

    public function testSaveAndFindById(): void
    {
        $product = new Product(
            new Uuid('123e4567-e89b-12d3-a456-426614174000'),
            'Test Product',
            100.0
        );

        $this->repository->save($product);

        $fetchedProduct = $this->repository->findById($product->getId());
        $this->assertNotNull($fetchedProduct);
        $this->assertEquals($product->getName(), $fetchedProduct->getName());
    }
}
