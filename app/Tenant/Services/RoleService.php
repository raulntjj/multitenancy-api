<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleService {

    public function findManyRoles() {
        return Role::all();
    }

    public function createRole(Array $data) {
      $role = Role::create([
        'role' => $data['role'],
      ]);

      if (!empty($data['permissions'])) {
          $role->permissions()->attach($data['permissions']);
      }

      return $role;
    }

    public function updateRole(array $data, String $roleName) {
        $role = Role::where('role', $roleName)->firstOrFail();

        $role->fill([
            'role' => $data['role'] ?? $role->role,
        ])->save();

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $role;
    }
    
    public function deleteRole(String $roleName) {
        return Role::where('role', $roleName)->delete();
    }
}