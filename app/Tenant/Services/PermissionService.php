<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Permission;

class PermissionService {

    public function findManyPermissions() {
        return Permission::all();
    }

    public function createPermission(Array $data) {
      $permission = Permission::create([
        'permission' => $data['permission'],
      ]);

      return $permission;
    }

    public function updatePermission(array $data, String $permissionName) {
        $permission = Permission::where('permission', $permissionName)->firstOrFail();

        $permission->fill([
            'permission' => $data['permission'] ?? $permission->permission,
        ])->save();

        return $permission;
    }
    
    public function deletePermission(String $permissionName) {
        return Permission::where('permission', $permipermissionNamession)->delete();
    }
}