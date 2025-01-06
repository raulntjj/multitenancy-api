<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Core\Models\Tenant;

class TenantFresh extends Command {
    protected $signature = 'tenant:fresh {slug?}';
    protected $description = 'Delete and recreate database schema for tenants';

    public function handle() {
        $slug = $this->argument('slug');

        if (!$slug) {
            return $this->freshAll();
        } else {
            return $this->freshOne($slug);
        }
    }

    private function freshAll() {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->line('Refreshing tenant: ' . $tenant->slug);
            $this->freshTenant($tenant);
        }

        $this->info('All tenants refreshed.');
        return 0;
    }

    private function freshOne($slug) {
        $tenant = Tenant::where('slug', $slug)->first();

        if (!$tenant) {
            $this->error("Tenant '{$slug}' not found!");
            return 1;
        }

        $this->line("Refreshing tenant: {$tenant->slug}");
        $this->freshTenant($tenant);

        $this->info("Tenant '{$slug}' refreshed!");
        return 0;
    }

    private function freshTenant(Tenant $tenant) {
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

        $this->line('Dropping all tables for tenant: ' . $tenant->slug);
        Artisan::call('db:wipe', [
            '--database' => 'tenant',
            '--force'    => true,
        ]);
        $this->line(Artisan::output());

        $this->line('Running migrations for tenant: ' . $tenant->slug);
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path'     => 'database/migrations/tenant',
            '--force'    => true,
        ]);
        $this->line(Artisan::output());
    }
}
