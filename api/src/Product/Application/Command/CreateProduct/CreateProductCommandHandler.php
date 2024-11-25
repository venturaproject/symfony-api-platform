<?php

declare(strict_types=1);

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidGenerator\UuidGeneratorInterface;
use App\Shared\Application\Service\EventPublisher;
use App\Product\Domain\Event\ProductCreatedEvent;
use Psr\Log\LoggerInterface;

class CreateProductCommandHandler implements CommandHandlerInterface
{
    private ProductRepositoryInterface $productRepository;
    private UuidGeneratorInterface $uuidGenerator;
    private EventPublisher $eventPublisher;
    private LoggerInterface $logger;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        UuidGeneratorInterface $uuidGenerator,
        EventPublisher $eventPublisher,
        LoggerInterface $logger // Logger
    ) {
        $this->productRepository = $productRepository;
        $this->uuidGenerator = $uuidGenerator;
        $this->eventPublisher = $eventPublisher;
        $this->logger = $logger;
    }

    public function __invoke(CreateProductCommand $command): ProductDTO
    {
        $this->logger->info('Processing CreateProductCommand', [
            'name' => $command->name,
            'price' => $command->price,
            'description' => $command->description
        ]);

        $dateAdd = $command->date_add ?? new \DateTime();
        $product = new Product(
            id: new \App\Shared\Domain\ValueObject\Uuid($this->uuidGenerator->generate()),
            name: $command->name,
            price: $command->price,
            description: $command->description,
            date_add: $dateAdd
        );

        $this->productRepository->save($product);

        $this->eventPublisher->publish(new ProductCreatedEvent($product, new \DateTimeImmutable()));

        $this->logger->info('Product created successfully', ['product_id' => $product->getId()->__toString()]);

        return new ProductDTO(
            id: $product->getId()->__toString(),
            name: $product->getName(),
            price: $product->getPrice(),
            description: $product->getDescription(),
            date_add: $product->getDateAdd()->format('Y-m-d')
        );
    }
}

