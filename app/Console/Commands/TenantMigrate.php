<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Core\Models\Tenant;

class TenantMigrate extends Command {
    protected $signature = 'tenant:migrate {slug?}';
    protected $description = 'Starting migrations';

    public function handle() {
        $slug = $this->argument('slug');
        
        if (!$slug) {
            return $this->migrateAll();
        } else {
            return $this->migrateOne($slug);
        }
    }

    private function migrateAll() {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->line('Migrando tenant: ' . $tenant->slug);
            $this->migrateTenant($tenant);
        }

        $this->info('Migração de todos os tenants finalizada!');
        return 0;
    }

    private function migrateOne($slug) {
        $tenant = Tenant::where('slug', $slug)->first();

        if (!$tenant) {
            $this->error("Tenant '{$slug}' não encontrado!");
            return 1;
        }

        $this->line("Migrando tenant: {$tenant->slug}");
        $this->migrateTenant($tenant);

        $this->info("Migração do tenant '{$slug}' finalizada!");
        return 0;
    }

    private function migrateTenant(Tenant $tenant) {
        config([
            'database.connections.tenant' => [
                'driver'    => 'mysql',
                'host'      => $tenant->db_host,
                'database'  => $tenant->db_name,
                'username'  => $tenant->db_user,
                'password'  => $tenant->db_password,
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--force'    => true,
        ]);

        $this->line(Artisan::output());
    }
}
