<?php

namespace App\Core\Traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Core\Services\UserService;
use App\Exceptions\HandleException;

trait AuthenticatedUser {
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    
    private function getAuthenticatedUser($token) {
        if (!$token) {
            throw new HandleException('Token not provided', 401);
        }

        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        $id = $decoded->sub;

        $user = $this->userService->findById($id);

        if (!$user) {
            throw new HandleException('User not found', 404);
        }

        return $user;
    }
}