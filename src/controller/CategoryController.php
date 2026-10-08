<?php

namespace App\controller;

use App\service\CategoryService;
use OpenApi\Attributes as OAT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    /**
     * Returns all categories.
     */
    #[OAT\Get(
        path: '/api/v1/categories',
        summary: 'Get all categories',
        tags: ['Categories'],
        security: [['cookieAuth' => []]],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'List of categories'
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
    public function getCategories(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $result = $this->categoryService
            ->getCategories();

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
     * Returns one category by ID.
     */
    #[OAT\Get(
        path: '/api/v1/category/{category_id}',
        summary: 'Get one category',
        tags: ['Categories'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'Category ID',
                schema: new OAT\Schema(
                    type: 'integer'
                ),
                example: 1
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Category found'
            ),
            new OAT\Response(
                response: 400,
                description: 'Invalid category ID'
            ),
            new OAT\Response(
                response: 401,
                description: 'Not authenticated'
            ),
            new OAT\Response(
                response: 404,
                description: 'Category not found'
            )
        ]
    )]
    public function getCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->categoryService
            ->getCategory($args);

        return $this->result(
            $response,
            $result
        );
    }

    /**
     * Creates a new category.
     */
    #[OAT\Post(
        path: '/api/v1/category',
        summary: 'Create category',
        tags: ['Categories'],
        security: [['cookieAuth' => []]],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                required: [
                    'active',
                    'name'
                ],
                properties: [
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Gaming'
                    )
                ]
            )
        ),
        responses: [
            new OAT\Response(
                response: 201,
                description: 'Category created'
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
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function createCategory(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $result = $this->categoryService
            ->createCategory($request);

        if (isset($result['error'])) {
            return $this->json(
                $response,
                $result,
                400
            );
        }

        if (isset($result['fatal_error'])) {
            return $this->json(
                $response,
                $result,
                500
            );
        }

        return $this->json(
            $response,
            $result,
            201
        );
    }

    /**
     * Partially updates an existing category.
     */
    #[OAT\Patch(
        path: '/api/v1/category/{category_id}',
        summary: 'Update category',
        tags: ['Categories'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'Category ID',
                schema: new OAT\Schema(
                    type: 'integer'
                ),
                example: 1
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Hardware'
                    )
                ]
            )
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Category updated'
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
    public function updateCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->categoryService
            ->updateCategory(
                $request,
                $args
            );

        return $this->result(
            $response,
            $result
        );
    }

    /**
     * Deletes a category by ID.
     */
    #[OAT\Delete(
        path: '/api/v1/category/{category_id}',
        summary: 'Delete category',
        tags: ['Categories'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'Category ID',
                schema: new OAT\Schema(
                    type: 'integer'
                ),
                example: 1
            )
        ],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Category deleted'
            ),
            new OAT\Response(
                response: 400,
                description: 'Invalid category ID'
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
                response: 409,
                description: 'Category is still used by products'
            ),
            new OAT\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function deleteCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $result = $this->categoryService
            ->deleteCategory($args);

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

        if (isset($result['conflict'])) {
            return $this->json(
                $response,
                $result,
                409
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
     * Converts a service result into the correct HTTP response.
     */
    private function result(
        ResponseInterface $response,
        array $result
    ): ResponseInterface {

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

        return $this->json(
            $response,
            $result,
            200
        );
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