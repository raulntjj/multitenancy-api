<?php

namespace Tests\Unit\Core;

use App\Core\Models\Tenant;
use App\Core\Services\TenantService;
use Database\Factories\Core\TenantFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\CoreCase;

class TenantServiceTest extends CoreCase {
    use DatabaseMigrations;

    public function testFindManyTenants() {
        $tenants = TenantFactory::new()->count(3)->raw();
        // Salvando
        foreach ($tenants as $tenant) {
            Tenant::create($tenant);
        }
        $service = new TenantService();
        $tenants = $service->findManyTenants();

        $this->assertCount(3, $tenants);
    }

    public function testFindTenant() {
        $tenant = TenantFactory::new()->create(['slug' => 'test-slug']);
        $service = new TenantService();
        $foundTenant = $service->findTenant('test-slug');

        $this->assertEquals($tenant->id, $foundTenant->id);
    }

    public function testCreateTenant() {
        $service = new TenantService();
        $tenant = $service->createTenant([
            'name' => 'eedja',
            'slug' => 'eedja',
            'db_host' => '127.0.0.1:3306',
            'db_name' => 'eedja',
            'db_user' => 'root',
            'db_password' => 'root',
        ]);

        $this->assertDatabaseHas('tenants', ['name' => 'eedja']);
    }

    public function testUpdateTenant() {
        $tenant = TenantFactory::new()->create(['slug' => 'test-slug', 'name' => 'Old Name']);
        $service = new TenantService();
        $updatedTenant = $service->updateTenant(['name' => 'New Name'], 'test-slug');

        $this->assertEquals('New Name', $updatedTenant->name);
    }

    public function testDeleteTenant() {
        $tenant = TenantFactory::new()->create(['slug' => 'test-slug']);
        $service = new TenantService();
        $service->deleteTenant('test-slug');

        $this->assertDatabaseMissing('tenants', ['slug' => 'test-slug']);
    }
}
