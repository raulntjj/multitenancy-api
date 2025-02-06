<?php

namespace App\Tenant\Http\Middleware;

use Closure;
use App\Core\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Exceptions\HandleException;

class IdentifyTenant {
    public function handle($request, Closure $next) {
        $tenantSlug = $request->header('X-Tenant');

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            throw new HandleException('Tenant not found', 404);
        }

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

        return $next($request);
    }
}
