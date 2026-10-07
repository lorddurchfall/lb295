<?php

namespace App\categories;

class Category
{
    public static function validateCreate(array $data): array
    {
        $errors = [];

        // Active
        if (!array_key_exists('active', $data)) {
            $errors[] = 'active is required';
        } elseif (!self::isValidBoolean($data['active'])) {
            $errors[] = 'active must be true, false, 0 or 1';
        }

        // Name
        if (!isset($data['name']) || !is_string($data['name']) || trim($data['name']) === '') {
            $errors[] = 'name is required and must be a string';
        } elseif (strlen($data['name']) > 500) {
            $errors[] = 'name must not be longer than 500 characters';
        }

        return $errors;
    }

    public static function validatePatch(array $data): array
    {
        $errors = [];

        // Active nur prüfen, wenn mitgeschickt
        if (array_key_exists('active', $data)) {
            if (!self::isValidBoolean($data['active'])) {
                $errors[] = 'active must be true, false, 0 or 1';
            }
        }

        // Name nur prüfen, wenn mitgeschickt
        if (array_key_exists('name', $data)) {
            if (!is_string($data['name']) || trim($data['name']) === '') {
                $errors[] = 'name must be a non-empty string';
            } elseif (strlen($data['name']) > 500) {
                $errors[] = 'name must not be longer than 500 characters';
            }
        }

        // PATCH ohne Daten bringt nichts
        if (empty($data)) {
            $errors[] = 'at least one field must be provided';
        }

        return $errors;
    }

    private static function isValidBoolean($value): bool
    {
        return is_bool($value) || $value === 0 || $value === 1;
    }
}