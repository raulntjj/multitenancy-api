<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\PermissionService;
use App\Exceptions\HandleException;

class PermissionController extends Controller {
    protected $permissionService;
    public function __construct(PermissionService $permissionService) {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request) {
        try {
            $permissions = $this->permissionService->findManyPermissions();
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function create(Request $request) {
        try {
            $permissions = $this->permissionService->createPermission($request->all());
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, String $permission) {
        try {
            $permissions = $this->permissionService->updatePermission($request->all(), $permission);
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request) {
        try {
            $permissions = $this->permissionService->deletePermission();
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}