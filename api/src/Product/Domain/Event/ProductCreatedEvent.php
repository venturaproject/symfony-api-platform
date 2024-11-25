<?php

namespace App\Product\Domain\Event;

use App\Product\Domain\Entity\Product;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

class ProductCreatedEvent extends Event
{
    private Product $product;
    private DateTimeImmutable $dateTime;

    public function __construct(Product $product, DateTimeImmutable $dateTime)
    {
        $this->product = $product;
        $this->dateTime = $dateTime;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductName(): string
    {
        return $this->product->getName();
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}


