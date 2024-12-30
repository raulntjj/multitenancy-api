<?php

namespace App\Core\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CoreAuthMiddleware {
    public function handle($request, Closure $next) {
        $header = $request->header('Authorization');
        if (!$header) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = trim(str_replace('Bearer', '', $header));

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            if (empty($decoded->role) || $decoded->role !== 'core') {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            $request->attributes->add(['jwt_decoded' => $decoded]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
