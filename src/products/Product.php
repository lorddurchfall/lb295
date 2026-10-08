<?php

namespace App\products;

class Product
{
    /**
     * Validates all product data.
     */
    public static function validate(array $data): array
    {
        $errors = [];

        // Validate SKU.
        if (
            !isset($data['sku']) ||
            !is_string($data['sku']) ||
            trim($data['sku']) === ''
        ) {
            $errors[] = 'Invalid SKU';

        } elseif (strlen($data['sku']) > 100) {
            $errors[] = 'SKU must not exceed 100 characters';
        }

        // Validate active.
        if (
            !array_key_exists('active', $data) ||
            !in_array($data['active'], [0, 1], true)
        ) {
            $errors[] = 'Invalid active';
        }

        // Validate category.
        if (!array_key_exists('id_category', $data)) {
            $errors[] = 'id_category is required';

        } elseif (
            $data['id_category'] !== null &&
            (!is_int($data['id_category']) || $data['id_category'] <= 0)
        ) {
            $errors[] = 'Invalid category';
        }

        // Validate name.
        if (
            !isset($data['name']) ||
            !is_string($data['name']) ||
            trim($data['name']) === ''
        ) {
            $errors[] = 'Invalid name';

        } elseif (strlen($data['name']) > 500) {
            $errors[] = 'Name must not exceed 500 characters';
        }

        // Validate image.
        if (
            !isset($data['image']) ||
            !is_string($data['image'])
        ) {
            $errors[] = 'Invalid image';

        } elseif (strlen($data['image']) > 1000) {
            $errors[] = 'Image URL must not exceed 1000 characters';
        }

        // Validate description.
        if (
            !isset($data['description']) ||
            !is_string($data['description'])
        ) {
            $errors[] = 'Invalid description';
        }

        // Validate price.
        if (
            !isset($data['price']) ||
            !is_numeric($data['price']) ||
            (float) $data['price'] < 0
        ) {
            $errors[] = 'Invalid price';

        } elseif (
            round((float) $data['price'], 2) != (float) $data['price']
        ) {
            $errors[] = 'Price must have at most 2 decimal places';
        }

        // Validate stock.
        if (
            !isset($data['stock']) ||
            !is_int($data['stock']) ||
            $data['stock'] < 0
        ) {
            $errors[] = 'Invalid stock';
        }

        return $errors;
    }
}