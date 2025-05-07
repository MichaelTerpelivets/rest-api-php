<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductDTO;
use Contracts\ProductRepositoryInterface;

readonly class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    )
    {
    }

    public function createProduct(ProductDTO $dto): ProductDTO
    {
        $productData = $this->repository->create($dto);
        return $this->mapToDTO($productData);
    }

    public function getProductById(string $id): ?ProductDTO
    {
        $productData = $this->repository->find($id);
        if ($productData === null) {
            return null;
        }

        return $this->mapToDTO($productData);
    }

    public function updateProduct(string $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteProduct(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function listProducts(array $filters = []): array
    {
        $products = $this->repository->list($filters);

        return array_map([$this, 'mapToDTO'], $products);
    }

    private function mapToDTO(array $data): ProductDTO
    {
        return new ProductDTO(
            id: $data['id'],
            name: $data['name'],
            price: (float)$data['price'],
            category: $data['category'],
            attributes: is_array($data['attributes']) ? $data['attributes'] : json_decode((string)$data['attributes'], true) ?? []
        );
    }


}