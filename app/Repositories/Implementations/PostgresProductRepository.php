<?php

namespace Implementations;

use App\DTO\ProductDTO;
use PDO;
use Contracts\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

readonly class PostgresProductRepository implements ProductRepositoryInterface
{
    public function __construct(private PDO $db)
    {
    }

    public function create(ProductDTO $dto): array
    {
        $id = Uuid::uuid4()->toString();
        $stmt = $this->db->prepare("INSERT INTO products (id, name, price, category, attributes, created_at)
            VALUES (:id, :name, :price, :category, :attributes, NOW())");
        $stmt->execute([
            'id' => $id,
            'name' => $dto->name,
            'price' => $dto->price,
            'category' => $dto->category,
            'attributes' => json_encode($dto->attributes),
        ]);
        return $this->find($id);
    }

    public function find(string $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function update(string $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = "name = :name";
            $params['name'] = $data['name'];
        }

        if (isset($data['price'])) {
            $fields[] = "price = :price";
            $params['price'] = $data['price'];
        }

        if (isset($data['category'])) {
            $fields[] = "category = :category";
            $params['category'] = $data['category'];
        }

        if (isset($data['attributes'])) {
            $fields[] = "attributes = :attributes";
            $params['attributes'] = json_encode($data['attributes']);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function list(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = "category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['min_price'])) {
            $where[] = "price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = "price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        $sql = "SELECT * FROM products";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}