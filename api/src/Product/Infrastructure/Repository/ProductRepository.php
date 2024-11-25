<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product); 
        $this->entityManager->flush(); 

    }

    public function remove(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

    }

    public function findById(Uuid $id): ?Product 
    {
        return $this->entityManager->getRepository(className: Product::class)->find($id);
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }
}

