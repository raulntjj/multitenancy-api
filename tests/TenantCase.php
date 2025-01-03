<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Core\Models\Tenant;

abstract class TenantCase extends BaseTestCase {
    public function setUp(): void {
        parent::setUp();
    
        // $this->artisan('migrate');
    
        $tenant = Tenant::create([
            'name' => 'tenant_test',
            'slug' => 'tenant_test',
            'db_host' => '127.0.0.1:3306',
            'db_name' => 'tenant_test',
            'db_user' => 'root',
            'db_password' => 'root'
        ]);
    
        // $this->artisan('tenant:migrate');
    
        config([
            'database.connections.tenant' => [
                'driver'   => 'mysql',
                'host'     => $tenant->db_host,
                'database' => $tenant->db_name,
                'username' => $tenant->db_user,
                'password' => $tenant->db_password,
                'charset'  => 'utf8mb4',
                'collation'=> 'utf8mb4_unicode_ci',
            ],
        ]);
    
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');
    }
    
    protected function tearDown(): void {
        $this->truncateAllTables('core_test');
        $this->truncateAllTables('tenant_test');
        
        parent::tearDown();
    }
    
    protected function truncateAllTables(string $database): void {
        DB::statement("USE `$database`");
    
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
        $tables = DB::select('SHOW TABLES');
    
        $tableKey = "Tables_in_{$database}";
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            DB::statement("TRUNCATE TABLE `$tableName`");
        }
    
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    } 

    public function createApplication() {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function assertDatabaseHas($table, array $data) {
        $this->assertGreaterThan(0, app('db')->table($table)->where($data)->count(), sprintf(
            "Failed asserting that a row in the table [%s] matches the attributes %s.",
            $table,
            json_encode($data)
        ));
    }

    public function assertDatabaseMissing($table, array $data) {
        $this->assertEquals(0, app('db')->table($table)->where($data)->count(), sprintf(
            "Failed asserting that no row in the table [%s] matches the attributes %s.",
            $table,
            json_encode($data)
        ));
    }
}
