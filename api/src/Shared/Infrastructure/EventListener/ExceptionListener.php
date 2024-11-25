<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $violations = $exception->getViolations();

            $errors = $this->formatValidationErrors($violations);

            $response = new JsonResponse([
                'status' => 400,
                'error' => 'Validation errors occurred',
                'detail' => $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);

            $event->setResponse($response);
            return;
        }
    }

    /**
     * @param ConstraintViolationListInterface $violations
     * @return array<array<string, string>> An array of associative arrays with 'field' and 'message' keys, where message is always a string
     */
    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => (string) $violation->getMessage(),  
            ];
        }
        return $errors;
    }
}

