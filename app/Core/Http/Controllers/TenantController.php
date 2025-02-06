<?php

namespace App\Core\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Exceptions\HandleException;
use App\Core\Services\TenantService;
use App\Core\Services\UserService;
use App\Core\Traits\HasAuthenticatedUser;

class TenantController extends Controller {
    use HasAuthenticatedUser;

    protected $tenantService;
    protected $userService;

    public function __construct(
        TenantService $tenantService,
        UserService $userService
    ) {
        $this->tenantService = $tenantService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $tenants = $this->tenantService->findManyTenants();
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $tenants,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function show(Request $request, String $slug) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $tenant = $this->tenantService->findTenant($slug);
            if (!$tenant) {
                throw new HandleException('Tenant not found', 404);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $tenant,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $tenant = $this->tenantService->createTenant($request->all());
            if (!$tenant) {
                throw new HandleException('An error ocurred', 500);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $tenant,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, String $slug) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $tenant = $this->tenantService->updateTenant($request->all(), $slug);
            if (!$tenant) {
                throw new HandleException('Tenant not found', 404);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $tenant,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request, String $slug) {
        try {
            $user = $this->getHasAuthenticatedUser($request->bearerToken());
            $tenant = $this->tenantService->deleteTenant($slug);
            if (!$tenant) {
                throw new HandleException('Tenant not found', 404);
            }
            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'details' => 'Tenant deleted succefully!',
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}