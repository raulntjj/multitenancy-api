<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\UserService;
use App\Tenant\Traits\AuthenticatedUser;
use App\Exceptions\HandleException;

class UserController extends Controller {
    use AuthenticatedUser;
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(Request $request) {
        try {
            $users = $this->userService->findManyUsers();
            if (!$users) {
                throw new HandleException('User not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $users,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function show(Request $request, String $user) {
        try {
            $user = $this->userService->findByUser($user);
            if (!$user) {
                throw new HandleException('User not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function create(Request $request) {
        try {
            $user = $this->userService->createUser($request->all());
            if (!$user) {
                throw new HandleException('Something went wrong', 500);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function update(Request $request, String $user) {
        try {
            $user = $this->userService->updateUser($request->all(), $user);
            if (!$user) {
                throw new HandleException('User not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function destroy(Request $request, String $user) {
        try {
            $user = $this->userService->deleteUser($user);
            if (!$user) {
                throw new HandleException('User not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function syncRoles(Request $request, String $user) {
        try {
            $user = $this->userService->syncRolesToUser($data['roles'], $user);
    
            return response()->json([
                'status_code' => 204,
                'status' => 'success',
                'details' => user
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }
}