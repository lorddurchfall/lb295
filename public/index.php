<?php

use App\database\Database;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

// Load Composer autoload.
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create the Slim application.
$app = AppFactory::create();

// Enable JSON request body parsing.
$app->addBodyParsingMiddleware();

// Create the database connection.
$database = new Database();
$db = $database->getConnection();

// Load all API routes.
require_once __DIR__ . '/../src/routes/routes.php';

// Start the application.
$app->run();