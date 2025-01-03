<?php

namespace App\Tenant\Http\Controllers;

use App\Tenant\Models\User;
use Database\Factories\UserFactory;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Exceptions\HandleException;
use App\Tenant\Services\UserService;

class AuthController extends Controller {

    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(Request $request) {
        try {
            $credentials = $request->only('email', 'password', 'user');
    
            $user = $this->userService->findUser($credentials);
            if (!$user) {
                throw new HandleException('Credentials does not match', 404);
            }
    
            if (!password_verify($credentials['password'], $user->password)) {
                throw new HandleException('Credentials does not match', 401);
            }

            $payload = [
                'iss'  => 'core-api',
                'sub'  => $user->id,
                'role' => 'tenant',
                'iat'  => time(),
                'exp'  => time() + 3600,
            ];
            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => [
                    'user'  => $user,
                    'token' => 'Bearer ' . $token,
                    'type' => 'Bearer',
                    'expires_in' => date('Y-m-d H:i:s', $payload['exp']),
                    'issuing_at' => date('Y-m-d H:i:s', $payload['iat']),
                ]
            ]);

            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function register(Request $request) {
        try {
            $user = $this->userService->createUser($request->all());
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => [
                    'user'  => $user
                ]
            ]);
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}
