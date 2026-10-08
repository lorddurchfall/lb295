<?php

namespace App\service;

use App\categories\Category;
use App\repository\CategoryRepository;
use App\repository\ProductRepository;
use Psr\Http\Message\ServerRequestInterface;

class CategoryService
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Returns all categories.
     */
    public function getCategories(): array
    {
        try {

            return $this->categoryRepository
                ->getAll();

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Returns one category by ID.
     */
    public function getCategory(array $args): array
    {
        $id = $args['category_id'] ?? null;

        if (!ctype_digit((string) $id)) {
            return [
                'error' => 'Invalid category id'
            ];
        }

        $id = (int) $id;

        try {

            $category = $this->categoryRepository
                ->getCategoryById($id);

            if ($category === null) {
                return [
                    'not_found' =>
                        'Category not found'
                ];
            }

            return $category;

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Creates a new category.
     */
    public function createCategory(
        ServerRequestInterface $request
    ): array {

        $data = $request->getParsedBody();

        if (!is_array($data)) {
            return [
                'error' => 'Invalid request body'
            ];
        }

        $errors = Category::validateCreate($data);

        if (!empty($errors)) {
            return [
                'error' => $errors[0]
            ];
        }

        try {

            if (
                $this->categoryRepository
                    ->existsByName($data['name'])
            ) {
                return [
                    'error' =>
                        'Category already exists'
                ];
            }

            return $this->categoryRepository
                ->createCategory(
                    $data['active'],
                    $data['name']
                );

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Updates an existing category.
     */
    public function updateCategory(
        ServerRequestInterface $request,
        array $args
    ): array {

        $id = $args['category_id'] ?? null;

        if (!ctype_digit((string) $id)) {
            return [
                'error' => 'Invalid category id'
            ];
        }

        $id = (int) $id;

        $data = $request->getParsedBody();

        if (!is_array($data)) {
            return [
                'error' => 'Invalid request body'
            ];
        }

        $errors = Category::validatePatch($data);

        if (!empty($errors)) {
            return [
                'error' => $errors[0]
            ];
        }

        try {

            $current = $this->categoryRepository
                ->getCategoryById($id);

            if ($current === null) {
                return [
                    'not_found' =>
                        'Category not found'
                ];
            }

            // Keep existing values when they were not provided.
            $active = array_key_exists(
                'active',
                $data
            )
                ? $data['active']
                : (int) $current['active'];

            $name = $data['name']
                ?? $current['name'];

            return $this->categoryRepository
                ->updateCategory(
                    $id,
                    $active,
                    $name
                );

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Deletes a category by ID.
     */
    public function deleteCategory(array $args): array
    {
        $id = $args['category_id'] ?? null;

        if (!ctype_digit((string) $id)) {
            return [
                'error' => 'Invalid category id'
            ];
        }

        $id = (int) $id;

        try {

            if (
                !$this->categoryRepository
                    ->existsById($id)
            ) {
                return [
                    'not_found' =>
                        'Category not found'
                ];
            }

            // Prevent deletion while products still use the category.
            if (
                $this->productRepository
                    ->productExistsWithCategoryId($id)
            ) {
                return [
                    'conflict' =>
                        'Category is still used by products'
                ];
            }

            $this->categoryRepository
                ->deleteCategory($id);

            return [
                'deleted' => true
            ];

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }
}