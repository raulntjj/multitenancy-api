<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Core\Models\Tenant;

class TenantRollback extends Command {
    protected $signature = 'tenant:rollback {slug?} {--step=1}';
    protected $description = 'Rollback migrations for tenants';

    public function handle() {
        $slug = $this->argument('slug');
        $step = $this->option('step');

        if (!$slug) {
            return $this->rollbackAll($step);
        } else {
            return $this->rollbackOne($slug, $step);
        }
    }

    private function rollbackAll($step) {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->line('Rolling back tenant: ' . $tenant->slug);
            $this->rollbackTenant($tenant, $step);
        }

        $this->info('Rollback completed for all tenants.');
        return 0;
    }

    private function rollbackOne($slug, $step) {
        $tenant = Tenant::where('slug', $slug)->first();

        if (!$tenant) {
            $this->error("Tenant '{$slug}' not found!");
            return 1;
        }

        $this->line("Rolling back tenant: {$tenant->slug}");
        $this->rollbackTenant($tenant, $step);

        $this->info("Rollback completed for tenant '{$slug}'.");
        return 0;
    }

    private function rollbackTenant(Tenant $tenant, $step) {
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

        Artisan::call('migrate:rollback', [
            '--database' => 'tenant',
            '--path'     => 'database/migrations/tenant',
            '--step'     => $step,
            '--force'    => true,
        ]);

        $this->line(Artisan::output());
    }
}
