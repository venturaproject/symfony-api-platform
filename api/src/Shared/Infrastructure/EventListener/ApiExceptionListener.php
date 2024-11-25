<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Product\Application\Exception\ProductNotFoundException;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ProductNotFoundException) {
            $data = [
                '@context' => '/contexts/Error',
                '@type' => 'Error',
                'title' => 'An error occurred',
                'description' => 'The product "' . $exception->getProductId() . '" does not exist.',
            ];

            $response = new JsonResponse($data, 404);
            $event->setResponse($response);
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $data = [
                'status' => $exception->getStatusCode(),
                'error' => $this->getErrorTitle($exception->getStatusCode()),
                'message' => $exception->getMessage(),
            ];

            $response = new JsonResponse($data, $exception->getStatusCode());
            $event->setResponse($response);
            return;
        }

        // Default response for any unexpected exception
        $data = [
            'status' => 500,
            'error' => 'Internal Server Error',
            'message' => 'An unexpected error occurred.',
        ];

        $response = new JsonResponse($data, 500);
        $event->setResponse($response);
    }

    private function getErrorTitle(int $statusCode): string
    {
        return match ($statusCode) {
            404 => 'Resource Not Found',
            403 => 'Forbidden',
            400 => 'Bad Request',
            default => 'An error occurred',
        };
    }
}
