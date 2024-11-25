<?php

declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProduct;

use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Service\UpdateProductService;
use App\Shared\Application\Command\CommandHandlerInterface;

class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UpdateProductService $updateProductService
    ) { }

    public function __invoke(UpdateProductCommand $command): ProductDTO
    {
     
        $product = $this->updateProductService->update(
            $command->id,
            $command->name,
            $command->price,
            $command->description, 
            $command->date_add
        );

        return new ProductDTO(
            (string) $product->getId(),
            $product->getName(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getDateAdd()->format('Y-m-d') 
        );
    }
}
