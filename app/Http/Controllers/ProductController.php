<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Services\ProductService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ProductController
{

    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $filters = $request->getQueryParams();
        $products = $this->service->listProducts($filters);

        $response->getBody()->write(json_encode($products));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode((string)$request->getBody(), true);
        if (!$this->validateProductData($data)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid product data']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }

        $dto = new ProductDTO(
            null,
            name: $data['name'],
            price: (float)$data['price'],
            category: $data['category'],
            attributes: $data['attributes'] ?? []
        );

        $product = $this->service->createProduct($dto);

        $response->getBody()->write(json_encode($product));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'] ?? null;
        $product = $this->service->getProductById($id);
        if (!$product) {
            $response->getBody()->write(json_encode(['error' => 'Product not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($product));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'] ?? null;
        $data = json_decode((string)$request->getBody(), true);

        if (!$id || !$this->service->updateProduct($id, $data)) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update product']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode(['message' => 'Product updated successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'] ?? null;

        if (!$id || !$this->service->deleteProduct($id)) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete product']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode(['message' => 'Product deleted successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }


    private function validateProductData(?array $data): bool
    {
        if (
            empty($data['name']) ||
            !isset($data['price']) ||
            !is_numeric($data['price']) ||
            empty($data['category'])
        ) {
            return false;
        }

        return true;
    }

}