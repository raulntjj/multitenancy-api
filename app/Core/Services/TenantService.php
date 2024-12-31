<?php

namespace App\Core\Services;

use App\Core\Models\Tenant;

class TenantService {

    public function findManyTenants() {
        return Tenant::all();
    }

    public function findTenant(String $slug) {
        return Tenant::where('slug', $slug)->first();
    }

    public function createTenant(Array $data) {
        return Tenant::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'db_host' => $data['db_host'],
            'db_name' => $data['db_name'],
            'db_user' => $data['db_user'],
            'db_password' => $data['db_password'],
        ]);
    }

    public function updateTenant(array $data, String $slug) {
        $tenant = Tenant::where('slug', $slug)->first();
        
        $tenant->fill([
            'name' => $data['name'] ?? $tenant->name,
            'slug' => $data['slug'] ?? $tenant->slug,
            'db_host' => $data['db_host'] ?? $tenant->db_host,
            'db_name' => $data['db_name'] ?? $tenant->db_name,
            'db_user' => $data['db_user'] ?? $tenant->db_user,
            'db_password' => $data['db_password'] ?? $tenant->db_password,
        ])->save();

        return $tenant;
    }
    
    public function deleteTenant(String $slug) {
        return Tenant::where('slug', $slug)->delete();
    }
}