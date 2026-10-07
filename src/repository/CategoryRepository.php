<?php

namespace App\repository;

use mysqli;

class CategoryRepository
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getCategories(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categories"
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categories WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function createCategory(string $name): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name)
             VALUES (?)"
        );

        $stmt->bind_param("s", $name);
        $stmt->execute();

        return $this->db->insert_id;
    }

    public function updateCategory(
        int    $id,
        string $name
    ): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categories
             SET name = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $name,
            $id
        );

        return $stmt->execute();
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}