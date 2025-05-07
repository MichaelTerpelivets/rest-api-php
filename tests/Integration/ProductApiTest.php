<?php

namespace Integration;

use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase
{
    private string $baseUrl;

    protected function setUp(): void
    {
        $this->baseUrl = 'http://localhost:8000/products';
    }

    public function testCreateProduct(): void
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 10.99,
            'category' => 'electronics',
            'attributes' => [
                'color' => 'black',
                'weight' => '1kg',
            ],
        ];

        $response = $this->makeRequest('POST', $this->baseUrl, $productData);
        $this->assertEquals(201, $response['statusCode'], "Expected status code 201, got {$response['statusCode']}.");
        $this->assertIsArray($response['body'], 'The response body should be an array.');
        $this->assertArrayHasKey('id', $response['body'], 'Response body does not contain the "id" key.');
        $this->assertEquals('Test Product', $response['body']['name'], 'Product name in the response does not match.');
    }

    public function testGetProduct(): void
    {
        $productId = $this->createTestProduct();

        $response = $this->makeRequest('GET', $this->baseUrl . '/' . $productId);
        $this->assertEquals(200, $response['statusCode'], "Expected status code 200, got {$response['statusCode']}.");
        $this->assertIsArray($response['body'], 'The response body should be an array.');
        $this->assertArrayHasKey('id', $response['body'], 'Response body does not contain the "id" key.');
        $this->assertEquals($productId, $response['body']['id'], 'The product ID in the response does not match.');
    }

    private function createTestProduct(): string
    {
        $productData = [
            'name' => 'Test Product 2',
            'price' => 10.99,
            'category' => 'electronics',
            'attributes' => [
                'color' => 'black',
                'weight' => '1kg',
            ],
        ];

        $response = $this->makeRequest('POST', $this->baseUrl, $productData);

        $this->assertEquals(201, $response['statusCode'], "Expected status code 201, got {$response['statusCode']}.");
        $this->assertIsArray($response['body'], 'The response body should be an array.');
        $this->assertArrayHasKey('id', $response['body'], 'The response body does not contain the "id" key.');

        return $response['body']['id'];
    }

    private function makeRequest(string $method, string $url, array $data = null): array
    {
        try {
            $options = [
                'http' => [
                    'method' => $method,
                    'header' => 'Content-Type: application/json',
                    'content' => $data ? json_encode($data) : '',
                    'ignore_errors' => true,
                ],
            ];

            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $statusCode = 0;

            if (isset($http_response_header)) {
                if (preg_match('/HTTP\/1\.[01] (\d{3})/', $http_response_header[0], $matches)) {
                    $statusCode = (int)$matches[1];
                }
            }

            $body = $response ? json_decode($response, true) : [];

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "JSON decode error: " . json_last_error_msg() . "\nResponse: $response\n";
                $body = ['error' => 'Invalid JSON response'];
            }

            return [
                'statusCode' => $statusCode,
                'body' => $body,
            ];
        } catch (\Throwable $e) {
            echo "Request failed: " . $e->getMessage() . "\n";
            error_log("Request failed: " . $e->getMessage());

            return [
                'statusCode' => 500,
                'body' => ['error' => 'Request failed: ' . $e->getMessage()],
            ];
        }
    }
}