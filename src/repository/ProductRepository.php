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

    /**
     * Returns all products.
     */
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                product_id AS id,
                sku,
                active,
                id_category,
                name,
                image,
                description,
                price,
                stock
             FROM product"
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Checks whether a product with the given SKU exists.
     */
    public function productExists(string $sku): bool
    {
        $stmt = $this->db->prepare(
            "SELECT product_id
             FROM product
             WHERE sku = ?"
        );

        $stmt->bind_param("s", $sku);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Returns one product by SKU.
     */
    public function getProductBySku(string $sku): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                product_id AS id,
                sku,
                active,
                id_category,
                name,
                image,
                description,
                price,
                stock
             FROM product
             WHERE sku = ?"
        );

        $stmt->bind_param("s", $sku);
        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc() ?: null;
    }

    /**
     * Creates a new product.
     */
    public function createProduct(
        string $sku,
        array $data
    ): array {

        $active = $data['active'];
        $categoryId = $data['id_category'];
        $name = $data['name'];
        $image = $data['image'];
        $description = $data['description'];
        $price = (float) $data['price'];
        $stock = $data['stock'];

        $stmt = $this->db->prepare(
            "INSERT INTO product
             (
                sku,
                active,
                id_category,
                name,
                image,
                description,
                price,
                stock
             )
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "siisssdi",
            $sku,
            $active,
            $categoryId,
            $name,
            $image,
            $description,
            $price,
            $stock
        );

        $stmt->execute();

        return $this->getProductBySku($sku);
    }

    /**
     * Updates an existing product.
     */
    public function updateProduct(
        string $sku,
        array $data
    ): array {

        $active = $data['active'];
        $categoryId = $data['id_category'];
        $name = $data['name'];
        $image = $data['image'];
        $description = $data['description'];
        $price = (float) $data['price'];
        $stock = $data['stock'];

        $stmt = $this->db->prepare(
            "UPDATE product
             SET active = ?,
                 id_category = ?,
                 name = ?,
                 image = ?,
                 description = ?,
                 price = ?,
                 stock = ?
             WHERE sku = ?"
        );

        $stmt->bind_param(
            "iisssdis",
            $active,
            $categoryId,
            $name,
            $image,
            $description,
            $price,
            $stock,
            $sku
        );

        $stmt->execute();

        return $this->getProductBySku($sku);
    }

    /**
     * Deletes a product by SKU.
     */
    public function deleteProduct(string $sku): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM product
             WHERE sku = ?"
        );

        $stmt->bind_param("s", $sku);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    /**
     * Checks whether a category is still used by a product.
     */
    public function productExistsWithCategoryId(
        int $categoryId
    ): bool {

        $stmt = $this->db->prepare(
            "SELECT product_id
             FROM product
             WHERE id_category = ?"
        );

        $stmt->bind_param("i", $categoryId);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}