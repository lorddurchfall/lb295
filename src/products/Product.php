<?php

namespace App\products;

class Product
{
    public static function validate(array $data): array
    {
        $errors = [];

        // SKU
        if (!isset($data['sku']) || !is_string($data['sku']) || trim($data['sku']) === '') {
            $errors[] = 'sku is required and must be a string';
        } elseif (strlen($data['sku']) > 100) {
            $errors[] = 'sku must not be longer than 100 characters';
        }

        // Active
        if (!array_key_exists('active', $data)) {
            $errors[] = 'active is required';
        } elseif (!self::isValidBoolean($data['active'])) {
            $errors[] = 'active must be true, false, 0 or 1';
        }

        // Category
        if (!array_key_exists('id_category', $data)) {
            $errors[] = 'id_category is required';
        } elseif (
            $data['id_category'] !== null &&
            (!is_int($data['id_category']) || $data['id_category'] <= 0)
        ) {
            $errors[] = 'id_category must be a positive integer or null';
        }

        // Name
        if (!isset($data['name']) || !is_string($data['name']) || trim($data['name']) === '') {
            $errors[] = 'name is required and must be a string';
        } elseif (strlen($data['name']) > 500) {
            $errors[] = 'name must not be longer than 500 characters';
        }

        // Image
        if (!isset($data['image']) || !is_string($data['image'])) {
            $errors[] = 'image is required and must be a string';
        } elseif (strlen($data['image']) > 1000) {
            $errors[] = 'image must not be longer than 1000 characters';
        }

        // Description
        if (!isset($data['description']) || !is_string($data['description'])) {
            $errors[] = 'description is required and must be a string';
        }

        // Price
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors[] = 'price is required and must be a number';
        } elseif ($data['price'] < 0) {
            $errors[] = 'price must not be negative';
        }

        // Stock
        if (!isset($data['stock']) || !is_int($data['stock'])) {
            $errors[] = 'stock is required and must be an integer';
        } elseif ($data['stock'] < 0) {
            $errors[] = 'stock must not be negative';
        }

        return $errors;
    }

    private static function isValidBoolean($value): bool
    {
        return is_bool($value) || $value === 0 || $value === 1;
    }
}