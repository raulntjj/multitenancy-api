<?php

namespace App\Core\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use App\Exceptions\HandleException;

class CoreAuthMiddleware {
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
            throw new HandleException($e->getMessage(), $e->getCode());
        } catch (SignatureInvalidException $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        } catch (\UnexpectedValueException $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }

        return $next($request);
    }
}
