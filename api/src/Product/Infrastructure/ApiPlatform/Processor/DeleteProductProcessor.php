<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Product\Application\Command\DeleteProduct\DeleteProductCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteProductProcessor implements ProcessorInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param mixed $data
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): JsonResponse {
        $id = $uriVariables['id'] ?? null;

        if (!$id) {
            return new JsonResponse(['error' => 'Product ID is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $uuid = new Uuid($id);

            $command = new DeleteProductCommand($uuid);
            $this->commandBus->execute($command);

            return new JsonResponse(['message' => 'Product deleted successfully'], JsonResponse::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => 'Invalid UUID format'], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while deleting the product'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
