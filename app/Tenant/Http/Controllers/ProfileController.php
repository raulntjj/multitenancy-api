<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\UserService;
use App\Tenant\Traits\HasAuthenticatedUser;
use App\Exceptions\HandleException;

class ProfileController extends Controller {
    use HasAuthenticatedUser;
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show(Request $request) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
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
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $user = $this->userService->updateUser($request->all(), $user->user);
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
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $user = $this->userService->deleteUser($user->user);
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