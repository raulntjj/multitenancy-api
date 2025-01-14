<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\RoleService;
use App\Exceptions\HandleException;

class RoleController extends Controller {
    protected $roleService;
    public function __construct(RoleService $roleService) {
        $this->roleService = $roleService;
    }

    public function index(Request $request) {
        try {
            $roles = $this->roleService->findManyRoles();
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function create(Request $request) {
        try {
            $roles = $this->roleService->createRole($request->all());
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function update(Request $request, String $role) {
        try {
            $roles = $this->roleService->updateRole($request->all(), $role);
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function destroy(Request $request) {
        try {
            $roles = $this->roleService->deleteRole();
            throw new HandleException("Something went wrong!", 500);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }
}