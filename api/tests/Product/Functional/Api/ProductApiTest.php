<?php

declare(strict_types=1);

namespace Tests\Product\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductApiTest extends WebTestCase
{
    public function testGetProductById(): void
    {
        $client = static::createClient();

        // Crear un producto para las pruebas
        $client->request('POST', '/api/products', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Test Product',
            'price' => 100.0,
            'description' => 'Test description'
        ]));

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);
        $productId = $data['id'];

        // Recuperar el producto por ID
        $client->request('GET', "/api/products/$productId");
        $this->assertResponseStatusCodeSame(200);

        $productData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Test Product', $productData['name']);
        $this->assertEquals(100.0, $productData['price']);
    }
}
