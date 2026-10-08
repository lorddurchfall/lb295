<?php

use App\controller\AuthController;
use App\controller\CategoryController;
use App\controller\ProductController;
use App\middleware\Middleware;
use App\repository\CategoryRepository;
use App\repository\ProductRepository;
use App\service\AuthService;
use App\service\CategoryService;
use App\service\ProductService;

/**
 *Shows on base localhost if it runs.
 */
$app->get('/', function ($request, $response) {
    $response->getBody()->write(
        json_encode([
            'message' => 'LB295 API is running'
        ])
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

/*
 * Create repositories.
 */
$productRepository =
    new ProductRepository($db);

$categoryRepository =
    new CategoryRepository($db);


/*
 * Create services.
 */
$productService =
    new ProductService(
        $productRepository,
        $categoryRepository
    );

$categoryService =
    new CategoryService(
        $categoryRepository,
        $productRepository
    );

$authService =
    new AuthService();


/*
 * Create controllers.
 */
$productController =
    new ProductController(
        $productService
    );

$categoryController =
    new CategoryController(
        $categoryService
    );

$authController =
    new AuthController(
        $authService
    );


/*
 * Create JWT middleware.
 */
$middleware =
    new Middleware();


/*
 * Authentication route.
 *
 * This is the only route that does not require a JWT.
 */
$app->post(
    '/api/v1/authenticate',
    [$authController, 'authenticate']
);


/*
 * Product routes.
 */
$app->get(
    '/api/v1/products',
    [$productController, 'getProducts']
)->addMiddleware($middleware);

$app->get(
    '/api/v1/product/{sku}',
    [$productController, 'getProduct']
)->addMiddleware($middleware);

$app->put(
    '/api/v1/product/{sku}',
    [$productController, 'putProduct']
)->addMiddleware($middleware);

$app->delete(
    '/api/v1/product/{sku}',
    [$productController, 'deleteProduct']
)->addMiddleware($middleware);


/*
 * Category routes.
 */
$app->get(
    '/api/v1/categories',
    [$categoryController, 'getCategories']
)->addMiddleware($middleware);

$app->get(
    '/api/v1/category/{category_id}',
    [$categoryController, 'getCategory']
)->addMiddleware($middleware);

$app->post(
    '/api/v1/category',
    [$categoryController, 'createCategory']
)->addMiddleware($middleware);

$app->patch(
    '/api/v1/category/{category_id}',
    [$categoryController, 'updateCategory']
)->addMiddleware($middleware);

$app->delete(
    '/api/v1/category/{category_id}',
    [$categoryController, 'deleteCategory']
)->addMiddleware($middleware);