<?php

namespace App\Tenant\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
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

            if (empty($decoded->role)) {
                throw new HandleException('Forbidden: Role not specified', 403);
            }

            $request->attributes->add(['jwt_decoded' => $decoded]);
        } catch (ExpiredException $e) {
            throw new HandleException('Token expired', 401);
        } catch (SignatureInvalidException $e) {
            throw new HandleException('Invalid token signature', 401);
        } catch (\UnexpectedValueException $e) {
            throw new HandleException('Malformed token', 400);
        } catch (\Exception $e) {
            throw new HandleException('Invalid token', 401);
        }

        return $next($request);
    }
}
