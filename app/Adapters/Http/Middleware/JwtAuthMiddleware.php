<?php

namespace App\Adapters\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado o inválido'], 401);
        }

        $token = str_replace('Bearer ', '', $header);

        try {
            $key = env('JWT_SECRET', 'default_secret');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Si se exige un rol específico, validamos que coincida o sea admin
            if ($role && $decoded->role !== $role && $decoded->role !== 'admin') {
                return response()->json(['error' => 'Acceso denegado: permisos insuficientes'], 403);
            }

            // Inyectamos los datos del usuario en la petición
            $request->merge([
                'auth_user_id' => $decoded->sub,
                'auth_user_role' => $decoded->role,
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido o expirado'], 401);
        }

        return $next($request);
    }
}