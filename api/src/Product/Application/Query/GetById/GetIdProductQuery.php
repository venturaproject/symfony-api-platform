<?php

namespace App\Product\Application\Query\GetById;

use App\Shared\Application\Query\QueryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetIdProductQuery implements QueryInterface
{
    private Uuid $productId;

    public function __construct(string $productId)
    {
        $this->productId = new Uuid($productId); 
    }

    public function getProductId(): Uuid
    {
        return $this->productId;
    }
}
