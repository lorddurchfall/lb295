<?php

namespace App\categories;

class Category
{
    /**
     * Validates data when creating a category.
     */
    public static function validateCreate(array $data): array
    {
        $errors = [];

        // Validate active.
        if (
            !array_key_exists('active', $data) ||
            !in_array($data['active'], [0, 1], true)
        ) {
            $errors[] = 'Invalid active';
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

        return $errors;
    }

    /**
     * Validates data when updating a category.
     */
    public static function validatePatch(array $data): array
    {
        $errors = [];

        if (empty($data)) {
            $errors[] = 'At least one field is required';
        }

        // Validate active only when it was provided.
        if (
            array_key_exists('active', $data) &&
            !in_array($data['active'], [0, 1], true)
        ) {
            $errors[] = 'Invalid active';
        }

        // Validate name only when it was provided.
        if (array_key_exists('name', $data)) {

            if (
                !is_string($data['name']) ||
                trim($data['name']) === ''
            ) {
                $errors[] = 'Invalid name';

            } elseif (strlen($data['name']) > 500) {
                $errors[] = 'Name must not exceed 500 characters';
            }
        }

        return $errors;
    }
}