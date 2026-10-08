<?php

namespace App\controller;

use App\service\AuthService;
use OpenApi\Attributes as OAT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    private AuthService $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    /**
     * Authenticates a user and creates a JWT.
     */
    #[OAT\Post(
        path: '/api/v1/authenticate',
        summary: 'Authenticate user',
        tags: ['Authentication'],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                required: [
                    'username',
                    'password'
                ],
                properties: [
                    new OAT\Property(
                        property: 'username',
                        type: 'string',
                        example: 'admin'
                    ),
                    new OAT\Property(
                        property: 'password',
                        type: 'string',
                        example: 'sec!ReT423*&'
                    )
                ]
            )
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Authentication successful',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'token',
                            type: 'string',
                            example: 'eyJhbGciOiJIUzI1NiJ9...'
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Authentication failed',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Wrong username or password'
                        )
                    ]
                )
            )
        ]
    )]
    public function authenticate(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $body = $request->getParsedBody();

        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        $result = $this->authService->login(
            $username,
            $password
        );

        if (isset($result['error'])) {

            $response->getBody()->write(
                json_encode($result)
            );

            return $response
                ->withStatus(401)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        // Store the JWT in an HTTP-only cookie.
        setcookie(
            'token',
            $result['token'],
            [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        $response->getBody()->write(
            json_encode($result)
        );

        return $response
            ->withStatus(200)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}