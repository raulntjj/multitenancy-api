<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TenantsMigration extends Command {
    protected $signature = 'tenants:migration 
    {--create= : Table name to create} 
    {--table= : Table name to update}';

    protected $description = 'Creating migration.';

    public function handle() {
        $create = $this->option('create');
        $table  = $this->option('table');

        if (!$create && !$table) {
            $this->error('Use --create for create_table or --table for alter_table');
            return 1;
        }

        if ($create) {
            $migrationName = 'create_' . $create . '_table';
        } else {
            $migrationName = 'update_' . $table . '_table'; 
        }

        $params = [
            'name'    => $migrationName,
            '--path'  => 'database/migrations/tenant',
        ];

        if ($create) {
            $params['--create'] = $create;
        }

        if ($table) {
            $params['--table'] = $table;
        }

        $this->call('make:migration', $params);

        return 0;
    }
}
