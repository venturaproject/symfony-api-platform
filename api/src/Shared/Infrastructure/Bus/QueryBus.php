<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    { 
        $this->messageBus = $queryBus;
    }

    public function execute(QueryInterface $command): mixed
    {
        try {
            return $this->handle($command);
        } catch (HandlerFailedException $exception) {
            $originalExeption = $exception->getPrevious();
            throw $originalExeption ?? $exception;
        }
    }
}