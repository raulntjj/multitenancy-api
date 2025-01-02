<?php

namespace App\Tenant\Http\Controllers;

use App\Tenant\Models\AuditLog;
use App\Exceptions\HandleException;
use Illuminate\Http\Request;

class AuditLogController {

    public function index(Request $request) {
        try {
            $auditLogs = new AuditLog($request->route('tenant') ?? 'core');
            $logs = $auditLogs->getLogs();
            if(!$logs) {
                throw new HandleException('Logs not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $logs,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}
