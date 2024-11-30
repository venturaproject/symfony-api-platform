<?php

declare(strict_types=1);

namespace Tests\Product\Unit\Application\Exception;

use App\Product\Application\Exception\ProductNotFoundException;
use PHPUnit\Framework\TestCase;

class ProductNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $exception = new ProductNotFoundException($uuid);
    
        $this->assertEquals("Product with ID \"$uuid\" not found.", $exception->getMessage());
    }
}
