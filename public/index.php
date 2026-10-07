<?php

use App\database\Database;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();

$database = new Database();
$db = $database->getConnection();

require_once __DIR__ . '/../src/routes/routes.php';

$app->run();