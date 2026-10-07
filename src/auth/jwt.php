<?php

namespace App\auth;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    private const EXPIRATION_TIME = 3600;

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

    public static function validateToken(string $token): bool
    {
        try {
            JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET'], 'HS256')
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}