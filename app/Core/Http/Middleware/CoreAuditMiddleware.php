<?php

namespace App\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use App\Core\Traits\HasAuthenticatedUser;
use Illuminate\Support\Carbon;

class CoreAuditMiddleware {
    use HasAuthenticatedUser;

    public function handle($request, Closure $next) {
        $response = $next($request);

        $tenant = $request->route('tenant') ?? 'core';
        $month = strtolower(Carbon::now()->format('F'));

        $baseLogDir = storage_path("logs/$tenant");
        $requestsLogFile = "$baseLogDir/requests.log";
        $auditLogFile = "$baseLogDir/audit.log";

        if (!is_dir($baseLogDir) && !mkdir($baseLogDir, 0755, true) && !is_dir($baseLogDir)) {
            throw new \RuntimeException(sprintf('O diretório "%s" não pôde ser criado.', $baseLogDir));
        }

        $requestLogger = new \Monolog\Logger('requests');
        $requestLogger->pushHandler(new StreamHandler($requestsLogFile, \Monolog\Logger::INFO));

        $auditLogger = new \Monolog\Logger('audit');
        $auditLogger->pushHandler(new StreamHandler($auditLogFile, \Monolog\Logger::ERROR));
        
        $user = $this->getHasAuthenticatedUser($request->bearerToken());

        $logData = [
            'tenant' => $tenant,
            'user_id' => $user->id ?? null,
            'name' => $user->name ?? 'guest',
            'ip' => $request->ip(),
            'route' => $request->path(),
            'method' => $request->method(),
            'status_code' => $response->status(),
            'description' => $request->header('X-Description') ?? null,
            'timestamp' => Carbon::now()->toIso8601String(),
        ];

        if ($response->status() >= 400) {
            $auditLogger->error('Audit Log:', $logData);
        } else {
            $requestLogger->info('Request Log:', $logData);
        }

        return $response;
    }
}
