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

    /**
     * Returns all categories.
     */
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                category_id AS id,
                active,
                name
             FROM category"
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Returns one category by ID.
     */
    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                category_id AS id,
                active,
                name
             FROM category
             WHERE category_id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc() ?: null;
    }

    /**
     * Checks whether a category exists.
     */
    public function existsById(int $id): bool
    {
        return $this->getCategoryById($id) !== null;
    }

    /**
     * Checks whether a category name already exists.
     */
    public function existsByName(string $name): bool
    {
        $stmt = $this->db->prepare(
            "SELECT category_id
             FROM category
             WHERE name = ?"
        );

        $stmt->bind_param("s", $name);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Creates a new category.
     */
    public function createCategory(
        int $active,
        string $name
    ): array {

        $stmt = $this->db->prepare(
            "INSERT INTO category
             (active, name)
             VALUES (?, ?)"
        );

        $stmt->bind_param(
            "is",
            $active,
            $name
        );

        $stmt->execute();

        return [
            'id' => $this->db->insert_id,
            'active' => $active,
            'name' => $name
        ];
    }

    /**
     * Updates an existing category.
     */
    public function updateCategory(
        int $id,
        int $active,
        string $name
    ): array {

        $stmt = $this->db->prepare(
            "UPDATE category
             SET active = ?, name = ?
             WHERE category_id = ?"
        );

        $stmt->bind_param(
            "isi",
            $active,
            $name,
            $id
        );

        $stmt->execute();

        return [
            'id' => $id,
            'active' => $active,
            'name' => $name
        ];
    }

    /**
     * Deletes a category by ID.
     */
    public function deleteCategory(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM category
             WHERE category_id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}