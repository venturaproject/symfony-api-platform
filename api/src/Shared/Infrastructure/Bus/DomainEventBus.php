<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Event\DomainEventBusInterface;
use App\Shared\Domain\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DomainEventBus implements DomainEventBusInterface
{
    public function __construct(private MessageBusInterface $messageBus){ }

    public function dispatch(EventInterface ...$event): void
    {
        foreach ($event as $e) {
            $this->messageBus->dispatch($e);
        }
    }
}