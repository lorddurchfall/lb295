<?php

namespace App\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    // The token is valid for one hour.
    private const EXPIRATION_TIME = 3600;

    /**
     * Creates a new signed JWT.
     */
    public static function createToken(string $username): string
    {
        $payload = [
            'username' => $username,
            'iat' => time(),
            'exp' => time() + self::EXPIRATION_TIME
        ];

        return JWT::encode(
            $payload,
            $_ENV['JWT_SECRET'],
            'HS256'
        );
    }

    /**
     * Checks whether a JWT is valid.
     */
    public static function validateToken(string $token): bool
    {
        try {
            JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET'], 'HS256')
            );

            return true;

        } catch (\Throwable $exception) {
            return false;
        }
    }
}