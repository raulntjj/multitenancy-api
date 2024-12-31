<?php

namespace App\Tenant\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Exceptions\HandleException;

class TenantAuthMiddleware {
    public function handle($request, Closure $next) {
        $header = $request->header('Authorization');
        if (!$header) {
            throw new HandleException('Token not provided', 403);
        }

        $token = trim(str_replace('Bearer', '', $header));
        
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            if (empty($decoded->role) || $decoded->role !== 'tenant') {
                throw new HandleException('Forbidden', 403);
            }

            $request->attributes->add(['jwt_decoded' => $decoded]);
        } catch (\Exception $e) {
            throw new HandleException('Invalid token', 401);
        }

        return $next($request);
    }
}
