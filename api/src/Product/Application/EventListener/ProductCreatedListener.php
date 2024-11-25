<?php

declare(strict_types=1);

namespace App\Product\Application\EventListener;

use App\Product\Domain\Event\ProductCreatedEvent;
use App\Shared\Application\Service\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductCreatedListener implements EventSubscriberInterface
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductCreatedEvent::class => 'onProductAdded',
        ];
    }

    public function onProductAdded(ProductCreatedEvent $event): void
    {
        $data = [
            'productName' => $event->getProductName(),
            'dateTime' => $event->getDateTime()->format('d-m-Y H:i:s'),
        ];

        $this->emailService->send(
            'emails/new_product.html.twig',
            $data,
            'recipient@example.com',
            'Nuevo Producto Añadido'
        );
    }
}
