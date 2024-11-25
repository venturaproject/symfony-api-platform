<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Event\EventInterface;

/**
 * Class AggregateRoot responsible for managing domain events 
 * and providing a method to release them.
 */
abstract class AggregateRoot
{
    /** @var EventInterface[] */
    protected array $domainEvents = [];

    /**
     * Records a domain event.
     * @param EventInterface $event
     */
    protected function recordEvent(EventInterface $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * Releases all recorded domain events.
     * @return EventInterface[]
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}