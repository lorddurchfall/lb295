<?php

namespace App\controller;

use OpenApi\Attributes as OAT;

/**
 * Contains the general OpenAPI configuration.
 */
#[OAT\Info(
    version: '1.0.0',
    title: 'UEK 295 Shop API',
    description: 'REST API for products and categories'
)]
#[OAT\SecurityScheme(
    securityScheme: 'cookieAuth',
    type: 'apiKey',
    name: 'token',
    in: 'cookie'
)]
class ApiDocumentation
{
}