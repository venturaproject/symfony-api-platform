<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use ApiPlatform\Metadata\Operation;
use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Infrastructure\ValidationDTO\UpdateProductDTO;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @template T1 of \App\Product\Application\DTO\ProductDTO
 * @template T2 of \Symfony\Component\HttpFoundation\JsonResponse
 * @implements ProcessorInterface<T1, JsonResponse>
 */
final class UpdateProductProcessor implements ProcessorInterface
{
    private CommandBusInterface $commandBus;
    private ValidatorInterface $validator;

    public function __construct(CommandBusInterface $commandBus, ValidatorInterface $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

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
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => 'Invalid UUID format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $inputDTO = new UpdateProductDTO(
            name: $data->name ?? '',
            price: $data->price ?? 0,
            description: $data->description ?? null,
            date_add: isset($data->date_add) 
                ? new \DateTimeImmutable($data->date_add) 
                : null
        );

        $violations = $this->validator->validate($inputDTO);

        if (count($violations) > 0) {
            return $this->formatValidationErrors($violations);
        }

        $command = new UpdateProductCommand(
            id: $uuid,
            name: $inputDTO->name,
            price: $inputDTO->price,
            description: $inputDTO->description,
            date_add: $inputDTO->date_add
        );

        try {
            $productDTO = $this->commandBus->execute($command);

            if (!$productDTO instanceof ProductDTO) {
                throw new \RuntimeException('The command did not return a ProductDTO.');
            }

            return new JsonResponse([
                'message' => 'Product updated successfully',
                'id' => $productDTO->id,
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while updating the product'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatValidationErrors(ConstraintViolationListInterface $violations): JsonResponse
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }
}

