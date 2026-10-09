<?php

use App\database\Database;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(false, true, true);
$app->addBodyParsingMiddleware();

$database = new Database();
$db = $database->getConnection();

require_once __DIR__ . '/../src/routes/routes.php';

$app->run();