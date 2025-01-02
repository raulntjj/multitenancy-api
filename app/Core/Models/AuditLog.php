<?php

namespace App\Core\Models;

use Illuminate\Support\Facades\File;

class AuditLog {
    protected $logPath;

    public function __construct() {
        $this->logPath = storage_path('logs/tenants/core/audit.log');
    }

    public function getLogs() {
        if (!File::exists($this->logPath)) {
            return [];
        }

        $logs = [];
        $lines = File::lines($this->logPath);

        foreach ($lines as $line) {
            if (strpos($line, 'Audit Log:') !== false) {
                $jsonString = trim(substr($line, strpos($line, '{')));
                $logs[] = json_decode($jsonString, true);
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
