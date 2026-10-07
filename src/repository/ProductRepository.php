<?php

namespace App\repository;

use mysqli;

class ProductRepository
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    // GET /products
    public function getProducts(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // GET /products/{sku}
    public function getProduct(string $sku): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM products WHERE sku = ?"
        );

        $stmt->bind_param("s", $sku);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // POST /products
    public function postProduct(
        string $sku,
        string $name,
        float $price,
        int $categoryId
    ): int {
        $existingProduct = $this->getProduct($sku);

        if ($existingProduct !== null) {
            $this->putProduct(
                $sku,
                $name,
                $price,
                $categoryId
            );

            return $existingProduct['id'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (sku, name, price, category_id)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdi",
            $sku,
            $name,
            $price,
            $categoryId
        );

        $stmt->execute();

        return $this->db->insert_id;
    }

    // PUT /products/{sku}
    public function putProduct(
        string $sku,
        string $name,
        float $price,
        int $categoryId
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE products
             SET name = ?, price = ?, category_id = ?
             WHERE sku = ?"
        );

        $stmt->bind_param(
            "sdis",
            $name,
            $price,
            $categoryId,
            $sku
        );

        return $stmt->execute();
    }

    // PATCH /products/{sku}
    public function patchProduct(
        string $sku,
        ?string $name = null,
        ?float $price = null,
        ?int $categoryId = null
    ): bool {
        $product = $this->getProduct($sku);

        if ($product === null) {
            return false;
        }

        $name = $name ?? $product['name'];
        $price = $price ?? $product['price'];
        $categoryId = $categoryId ?? $product['category_id'];

        return $this->putProduct(
            $sku,
            $name,
            $price,
            $categoryId
        );
    }

    // DELETE /products/{sku}
    public function deleteProduct(string $sku): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM products WHERE sku = ?"
        );

        $stmt->bind_param("s", $sku);

        return $stmt->execute();
    }
}