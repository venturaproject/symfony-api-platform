<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Subscriber;

use App\Shared\Domain\Exception\DomainExeption;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainExceptionSubscriber implements EventSubscriberInterface
{
   public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof DomainExeption) {
            return;
        }

        $code = match (true) {
            $exception instanceof NotFoundException => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        // Remove the null coalescing operator, as getMessage() should always return a string
        $response = new Response(
            $exception->getMessage(),    
            $code
        );

        $event->setResponse($response);
    }
}
