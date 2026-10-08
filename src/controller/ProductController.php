<?php

namespace App\controller;

use App\service\ProductService;
use OpenApi\Attributes as OAT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductController
{
    private ProductService $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * Creates or updates a product.
     */
    #[OAT\Put(
        path: '/api/v1/product/{sku}',
        summary: 'Create or update product',
        tags: ['Products'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'Product SKU',
                schema: new OAT\Schema(
                    type: 'string'
                ),
                example: 'PC-001'
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                required: [
                    'active',
                    'id_category',
                    'name',
                    'image',
                    'description',
                    'price',
                    'stock'
                ],
                properties: [
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'id_category',
                        type: 'integer',
                        nullable: true,
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Gaming PC'
                    ),
                    new OAT\Property(
                        property: 'image',
                        type: 'string',
                        example: 'https://example.com/pc.jpg'
                    ),
                    new OAT\Property(
                        property: 'description',
                        type: 'string',
                        example: 'A gaming PC'
                    ),
                    new OAT\Property(
                        property: 'price',
                        type: 'number',
                        format: 'float',
                        example: 1999.90
                    ),
                    new OAT\Property(
                        property: 'stock',
                        type: 'integer',
                        example: 5
                    )
                ]
            )
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Product updated'
            ),
            new OAT\Response(
                response: 201,
                description: 'Product created'
            ),
            new OAT\Response(
                response: 400,
                description: 'Invalid request'
            ),
            new OAT\Response(
                response: 401,
                description: 'Not authenticated'
            ),
            new OAT\Response(
                response: 404,
                description: 'Category not found'
            ),
            new OAT\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function putProduct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->productService
            ->putProduct(
                $request,
                $args
            );

        $code = 200;

        if (isset($result['created'])) {
            unset($result['created']);
            $code = 201;
        }

        if (isset($result['error'])) {
            $code = 400;
        }

        if (isset($result['not_found'])) {
            $code = 404;
        }

        if (isset($result['fatal_error'])) {
            $code = 500;
        }

        return $this->json(
            $response,
            $result,
            $code
        );
    }

    /**
     * Returns one product by SKU.
     */
    #[OAT\Get(
        path: '/api/v1/product/{sku}',
        summary: 'Get one product',
        tags: ['Products'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'Product SKU',
                schema: new OAT\Schema(
                    type: 'string'
                ),
                example: 'PC-001'
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Product found'
            ),
            new OAT\Response(
                response: 401,
                description: 'Not authenticated'
            ),
            new OAT\Response(
                response: 404,
                description: 'Product not found'
            ),
            new OAT\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function getProduct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->productService
            ->getProduct($args);

        $code = 200;

        if (isset($result['error'])) {
            $code = 400;
        }

        if (isset($result['not_found'])) {
            $code = 404;
        }

        if (isset($result['fatal_error'])) {
            $code = 500;
        }

        return $this->json(
            $response,
            $result,
            $code
        );
    }

    /**
     * Returns all products.
     */
    #[OAT\Get(
        path: '/api/v1/products',
        summary: 'Get all products',
        tags: ['Products'],
        security: [['cookieAuth' => []]],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'List of products'
            ),
            new OAT\Response(
                response: 401,
                description: 'Not authenticated'
            ),
            new OAT\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function getProducts(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $result = $this->productService
            ->getProducts();

        $code = isset($result['fatal_error'])
            ? 500
            : 200;

        return $this->json(
            $response,
            $result,
            $code
        );
    }

    /**
     * Deletes a product by SKU.
     */
    #[OAT\Delete(
        path: '/api/v1/product/{sku}',
        summary: 'Delete product',
        tags: ['Products'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'Product SKU',
                schema: new OAT\Schema(
                    type: 'string'
                ),
                example: 'PC-001'
            )
        ],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Product deleted'
            ),
            new OAT\Response(
                response: 401,
                description: 'Not authenticated'
            ),
            new OAT\Response(
                response: 404,
                description: 'Product not found'
            ),
            new OAT\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function deleteProduct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->productService
            ->deleteProduct($args);

        if (isset($result['error'])) {
            return $this->json(
                $response,
                $result,
                400
            );
        }

        if (isset($result['not_found'])) {
            return $this->json(
                $response,
                $result,
                404
            );
        }

        if (isset($result['fatal_error'])) {
            return $this->json(
                $response,
                $result,
                500
            );
        }

        return $response->withStatus(204);
    }

    /**
     * Creates a JSON response.
     */
    private function json(
        ResponseInterface $response,
        array $data,
        int $code
    ): ResponseInterface {

        $response->getBody()->write(
            json_encode($data)
        );

        return $response
            ->withStatus($code)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}