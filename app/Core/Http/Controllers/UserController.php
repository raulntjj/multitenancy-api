<?php

namespace App\Core\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Core\Services\UserService;
use App\Core\Traits\AuthenticatedUser;
use App\Exceptions\HandleException;

class UserController extends Controller {
    use AuthenticatedUser;
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            if (!$user) {
                throw new HandleException('User not found', 404);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            $user = $this->userService->updateUser($request->all(), $user->id);
            if (!$user) {
                throw new HandleException('User not found', 404);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            $user = $this->userService->deleteUser($user->id);
            if (!$user) {
                throw new HandleException('User not found', 404);
            }
            return response()->json([
                'status_code' => 204,
                'status' => 'success',
                'details' => 'User Deleted.'
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}