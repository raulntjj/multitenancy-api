<?php

namespace App\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use App\Core\Traits\AuthenticatedUser;
use Illuminate\Support\Carbon;

class CoreAuditMiddleware {
    use AuthenticatedUser;
    public function handle($request, Closure $next) {
        $response = $next($request);

        $tenant = $request->route('tenant') ?? 'core';
        $month = strtolower(Carbon::now()->format('F'));
        $year = Carbon::now()->year;

        $baseLogDir = storage_path("logs/tenants/$tenant/$month");

        if(
            $request->path() == 'api/v1/' . $tenant . '/login' ||
            $request->path() == 'api/v1/' . $tenant . '/register') {
            return $response;
        }

        if (!is_dir($baseLogDir) && !mkdir($baseLogDir, 0755, true) && !is_dir($baseLogDir)) {
            throw new \RuntimeException(sprintf('O diretório "%s" não pôde ser criado.', $baseLogDir));
        }

        // Configurar logs para erros e requisições
        $systemLogFile = "$baseLogDir/audit.log";
        $requestLogFile = "$baseLogDir/requests.log";

        $systemLogger = new \Monolog\Logger('system');
        $systemLogger->pushHandler(new StreamHandler($systemLogFile, \Monolog\Logger::INFO));

        $requestLogger = new \Monolog\Logger('requests');
        $requestLogger->pushHandler(new StreamHandler($requestLogFile, \Monolog\Logger::INFO));

        // Dados do log de requisições
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
            'timestamp' => Carbon::now()->toIso8601String(),
        ];

        // Registrar log de requisições
        $requestLogger->info('Request Log:', $logData);

        // Registrar log de sistema (erros ou outras informações gerais)
        if ($response->status() >= 400) {
            $systemLogger->error('System Log:', $logData);
        } else {
            $systemLogger->info('System Log:', $logData);
        }

        return $response;
    }
}
