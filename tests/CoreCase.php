<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Core\Models\Tenant;

abstract class CoreCase extends BaseTestCase {    
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
