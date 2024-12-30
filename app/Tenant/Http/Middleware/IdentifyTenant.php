<?php

namespace App\Tenant\Http\Middleware;

use Closure;
use App\Core\Models\Tenant;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        // Supondo que você identifique o "slug" do tenant via prefixo na URL
        // ex: /eedja/api/v1/... => "eedja"
        $tenantSlug = $request->route('tenant');

        // Busca no DB core
        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Configura a conexão "tenant"
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
