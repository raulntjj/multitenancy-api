<?php

namespace App\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Core\Traits\AuthenticatedUser;
use Monolog\Handler\StreamHandler;

class CoreAuditMiddleware {
    use AuthenticatedUser;

    public function handle($request, Closure $next) {
        $response = $next($request);

        $tenant = $request->route('tenant') ?? 'core';

        $tenantLogDir = storage_path('logs/tenants/' . $tenant);
        if (!is_dir($tenantLogDir) && !mkdir($tenantLogDir, 0755, true) && !is_dir($tenantLogDir)) {
            throw new \RuntimeException(sprintf('O diretório "%s" não pôde ser criado.', $tenantLogDir));
        }

        $monolog = Log::getLogger();
        $monolog->pushHandler(new StreamHandler(
            $tenantLogDir . '/audit.log',
            \Monolog\Logger::INFO
        ));

        $user = null;
        if ($request->bearerToken()) {
            $user = $this->getAuthenticatedUser($request->bearerToken());
        }

        $logData = [
            'tenant' => $tenant,
            'user_id' => $user->id ?? null,
            'name' => $user->name ?? 'guest',
            'ip' => $request->ip(),
            'route' => $request->path(),
            'method' => $request->method(),
            'status_code' => $response->status(),
            'timestamp' => \Illuminate\Support\Carbon::now(),
        ];

        $monolog->info('Audit Log:', $logData);

        return $response;
    }
}
