<?php

declare(strict_types=1);

namespace Tests\Product\Unit\Domain\Entity;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
        $product = new Product($uuid, 'Test Product', 99.99, 'A test description');

        $this->assertEquals($uuid, $product->getId());
        $this->assertEquals('Test Product', $product->getName());
        $this->assertEquals(99.99, $product->getPrice());
        $this->assertEquals('A test description', $product->getDescription());
        $this->assertInstanceOf(\DateTimeInterface::class, $product->getDateAdd());
    }

    public function testProductUpdate(): void
    {
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
        $product = new Product($uuid, 'Test Product', 99.99, 'A test description');

        $product->setName('Updated Name')->setPrice(120.0)->setDescription('Updated Description');

        $this->assertEquals('Updated Name', $product->getName());
        $this->assertEquals(120.0, $product->getPrice());
        $this->assertEquals('Updated Description', $product->getDescription());
    }
}
