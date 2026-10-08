<?php

namespace App\service;

use App\products\Product;
use App\repository\CategoryRepository;
use App\repository\ProductRepository;
use Psr\Http\Message\ServerRequestInterface;

class ProductService
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Creates a new product or updates an existing product.
     */
    public function putProduct(
        ServerRequestInterface $request,
        array $args
    ): array {

        $sku = $args['sku'] ?? null;
        $data = $request->getParsedBody();

        if (
            !is_string($sku) ||
            trim($sku) === ''
        ) {
            return [
                'error' => 'Invalid SKU'
            ];
        }

        if (!is_array($data)) {
            return [
                'error' => 'Invalid request body'
            ];
        }

        // The SKU comes from the URL parameter.
        $data['sku'] = $sku;

        $errors = Product::validate($data);

        if (!empty($errors)) {
            return [
                'error' => $errors[0]
            ];
        }

        if (
            $data['id_category'] !== null &&
            !$this->categoryRepository
                ->existsById($data['id_category'])
        ) {
            return [
                'not_found' => 'Category not found'
            ];
        }

        try {

            if (
                $this->productRepository
                    ->productExists($sku)
            ) {
                return $this->productRepository
                    ->updateProduct(
                        $sku,
                        $data
                    );
            }

            $result = $this->productRepository
                ->createProduct(
                    $sku,
                    $data
                );

            // This value is only used to determine HTTP status 201.
            $result['created'] = true;

            return $result;

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Returns one product by SKU.
     */
    public function getProduct(array $args): array
    {
        $sku = $args['sku'] ?? null;

        if (
            !is_string($sku) ||
            trim($sku) === ''
        ) {
            return [
                'error' => 'Invalid SKU'
            ];
        }

        try {

            $product = $this->productRepository
                ->getProductBySku($sku);

            if ($product === null) {
                return [
                    'not_found' =>
                        'Product not found'
                ];
            }

            return $product;

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Returns all products.
     */
    public function getProducts(): array
    {
        try {

            return $this->productRepository
                ->getAll();

        } catch (\Throwable $exception) {

            return [
                'fatal_error' =>
                    'An unexpected error occurred'
            ];
        }
    }

    /**
     * Deletes a product by SKU.
     */
    public function deleteProduct(array $args): array
    {
        $sku = $args['sku'] ?? null;

        if (
            !is_string($sku) ||
            trim($sku) === ''
        ) {
            return [
                'error' => 'Invalid SKU'
            ];
        }

        try {

            if (
                !$this->productRepository
                    ->productExists($sku)
            ) {
                return [
                    'not_found' =>
                        'Product not found'
                ];
            }

            $this->productRepository
                ->deleteProduct($sku);

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