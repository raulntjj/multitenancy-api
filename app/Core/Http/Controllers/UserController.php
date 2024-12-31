<?php

namespace App\Core\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Core\Models\User;
use App\Core\Services\UserService;
use App\Core\Traits\AuthenticatedUser;


class UserController extends Controller {
    use AuthenticatedUser;
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    public function update(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            $user = $this->userService->updateUser($request->all(), $user->id);
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    public function destroy() {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());
            $user = $this->userService->deleteUser($user->id);
            return response()->json([
                'status_code' => 204,
                'status' => 'success',
                'details' => 'User Deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}