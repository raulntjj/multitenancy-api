<?php

namespace App\Tenant\Models;

use Illuminate\Support\Facades\File;

class AuditLog {
    protected $logPath;

    public function __construct($tenant) {
        $this->logPath = storage_path('logs/tenants/' . $tenant . '/audit.log');
    }

    public function getLogs() {
        if (!File::exists($this->logPath)) {
            return [];
        }

        $logs = [];
        $lines = File::lines($this->logPath);

        foreach ($lines as $line) {
            // Extrai o JSON usando uma expressão regular
            if (preg_match('/\{.*\}/', $line, $matches)) {
                $logs[] = json_decode($matches[0], true);
            }
        }

        return $logs;
    }

    public function getLogsByUserId($userId) {
        $logs = $this->getLogs();
        return array_filter($logs, fn($log) => $log['user_id'] === $userId);
    }

    public function getLogsByDate($date) {
        $logs = $this->getLogs();
        return array_filter($logs, fn($log) => strpos($log['timestamp'], $date) === 0);
    }
}
