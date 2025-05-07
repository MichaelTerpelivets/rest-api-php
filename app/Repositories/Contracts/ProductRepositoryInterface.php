<?php

namespace Contracts;

use App\DTO\ProductDTO;

interface ProductRepositoryInterface
{
    public function create(ProductDTO $dto): array;

    public function find(string $id): ?array;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;

    public function list(array $filters = []): array;
}