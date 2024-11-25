<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Product\Domain\Service\DeleteProductService;
use App\Shared\Application\Command\CommandHandlerInterface;

class DeleteProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DeleteProductService $deleteProductService) { }

    public function __invoke(DeleteProductCommand $command): void
    {
       
        $this->deleteProductService->delete($command->id);
    }
}
