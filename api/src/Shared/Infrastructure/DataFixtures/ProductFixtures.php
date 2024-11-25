<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\DataFixtures;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\UuidGenerator\UuidGeneratorInterface;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;

class ProductFixtures extends Fixture
{
    private const API_URL = 'https://dummyjson.com/products';
    private const DEFAULT_PRODUCT_COUNT = 30;

    private HttpClientInterface $httpClient;
    private UuidGeneratorInterface $uuidGenerator;

    public function __construct(HttpClientInterface $httpClient, UuidGeneratorInterface $uuidGenerator)
    {
        $this->httpClient = $httpClient;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function load(ObjectManager $manager): void
    {
        try {
            $response = $this->httpClient->request('GET', self::API_URL);
            $productsData = $response->toArray()['products'] ?? [];
        } catch (
            TransportExceptionInterface |
            ClientExceptionInterface |
            ServerExceptionInterface |
            RedirectionExceptionInterface |
            DecodingExceptionInterface $e
        ) {
            throw new \RuntimeException('Error al conectar con la API de productos: ' . $e->getMessage());
        }

        $productsToCreate = array_slice($productsData, 0, self::DEFAULT_PRODUCT_COUNT);

        foreach ($productsToCreate as $data) {
            $uuid = new Uuid($this->uuidGenerator->generate());

            $product = new Product(
                id: $uuid,
                name: $data['title'],
                price: $data['price'],
                description: $data['description'] ?? null
            );

            $product->setDateAdd(Carbon::now());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
