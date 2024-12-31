<?php

namespace App\Core\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Core\Models\User;
use App\Core\Services\UserService;

class AuthController extends Controller {

    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(Request $request) {
        try {
            $credentials = $request->only('email', 'password');
    
            $user = $this->userService->findUser($credentials);

            if (!$user) {
                throw new \Exception('User not found', 404);
            }
    
            if (!password_verify($credentials['password'], $user->password)) {
                throw new \Exception('Invalid password', 401);
            }

            $payload = [
                'iss'  => 'core-api',
                'sub'  => $user->id,
                'role' => 'core',
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

            throw new Exception("Something went wrong!", 500);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
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
            throw new Exception("Something went wrong!", 500);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
