<?php

// Load Composer autoload.
require __DIR__ . '/../vendor/autoload.php';

// Load all controller files so swagger-php can find the attributes.
$controllers = glob(
    __DIR__ . '/../src/controller/*.php'
);

foreach ($controllers as $controller) {
    require_once $controller;
}

// Build the OpenAPI specification.
$result = (new \OpenApi\Builder())
    ->addSource(
        __DIR__ . '/../src'
    )
    ->build();

// Return the generated documentation as YAML.
header(
    'Content-Type: application/x-yaml'
);

echo $result->toYaml();