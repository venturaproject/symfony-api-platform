<?php

declare(strict_types=1);

namespace App\Shared\Application\Service;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;

class EventPublisher
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Publishes an event.
     * 
     * @param Event $event The event to be dispatched.
     */
    public function publish(Event $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}

