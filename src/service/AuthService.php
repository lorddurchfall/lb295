<?php

namespace App\service;

use App\auth\JwtAuth;

class AuthService
{
    /**
     * Validates login data and generates a JWT.
     */
    public function login(
        $username,
        $password
    ): array {

        if (
            !is_string($username) ||
            !is_string($password)
        ) {
            return [
                'error' => 'Wrong parameter type'
            ];
        }

        if (
            $username !== 'admin' ||
            $password !== 'sec!ReT423*&'
        ) {
            return [
                'error' => 'Wrong username or password'
            ];
        }

        return [
            'token' => JwtAuth::createToken($username)
        ];
    }
}