<?php

namespace App\middleware;

use App\auth\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class Middleware implements MiddlewareInterface
{
    /**
     * Checks whether a valid JWT cookie exists.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $token = $_COOKIE['token'] ?? null;

        if ($token === null) {
            return $this->sendUnauthorized(
                'Missing token'
            );
        }

        if (!JwtAuth::validateToken($token)) {
            return $this->sendUnauthorized(
                'Invalid token'
            );
        }

        return $handler->handle($request);
    }

    /**
     * Creates a 401 JSON response.
     */
    private function sendUnauthorized(
        string $message
    ): ResponseInterface {

        $response = new Response();

        $response->getBody()->write(
            json_encode([
                'error' => $message
            ])
        );

        return $response
            ->withStatus(401)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}