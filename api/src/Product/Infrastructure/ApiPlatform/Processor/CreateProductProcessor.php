<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use ApiPlatform\Metadata\Operation;
use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Infrastructure\ValidationDTO\CreateProductInputDTO;


final class CreateProductProcessor implements ProcessorInterface
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
    ): \Symfony\Component\HttpFoundation\JsonResponse { 
        $inputDTO = new CreateProductInputDTO(
            name: $data->name ?? '',
            price: $data->price ?? 0,
            description: $data->description ?? null,
            date_add: $data->date_add ?? null
        );

        // Validar datos
        $violations = $this->validator->validate($inputDTO);

        if (count($violations) > 0) {
            return $this->formatValidationErrors($violations);
        }

        $dateAdd = $inputDTO->date_add !== null ? new \DateTimeImmutable($inputDTO->date_add) : null;

        $command = new CreateProductCommand(
            name: $inputDTO->name,
            description: $inputDTO->description,
            price: $inputDTO->price,
            date_add: $dateAdd 
        );

        $productDTO = $this->commandBus->execute($command);

        if (!$productDTO instanceof ProductDTO) {
            throw new \RuntimeException('The command did not return a ProductDTO.');
        }

        // Retorno explícito de un JsonResponse
        return new JsonResponse([
            'message' => 'Product created successfully',
            'id' => $productDTO->id,
        ], JsonResponse::HTTP_CREATED);
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

