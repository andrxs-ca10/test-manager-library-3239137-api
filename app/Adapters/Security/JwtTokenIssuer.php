<?php

namespace App\Adapters\Security;

use App\Application\Ports\TokenIssuerInterface;
use Firebase\JWT\JWT;

class JwtTokenIssuer implements TokenIssuerInterface
{
    public function issue(string $sub, string $role): string
    {
        $key = env('JWT_SECRET', 'default_secret');
        $payload = [
            'sub' => $sub,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + (8 * 3600), // Vigencia de 8 horas exigida por el contrato
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}