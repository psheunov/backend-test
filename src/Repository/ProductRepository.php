<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Doctrine\DBAL\Connection;


class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM products WHERE uuid = :uuid',
            ['uuid' => $uuid]
        );

        if (empty($row)) {
            throw new Exception('Product not found');
        }

        return $this->make($row);
    }

    /**
     * @param array $uuids
     * @return Product[]
     */
    public function getByUuids(array $uuids): array
    {
        $result = [];
        $rows = $this->connection->fetchOne(
            "SELECT * FROM products WHERE uuid IN($uuids)",
        );

        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM products WHERE uuid IN (:uuids)',
            ['uuids' => $uuids]
        );

        foreach ($rows as $row) {
            $result[] = $this->make($row);
        }

        return $result;
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            fn(array $row): Product => $this->createProductFromRow($row),
            $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = 1 AND category = :category',
                ['category' => $category]
            )
        );
    }

    private function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
