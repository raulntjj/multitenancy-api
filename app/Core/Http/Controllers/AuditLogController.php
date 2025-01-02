<?php

namespace App\Core\Http\Controllers;

use App\Core\Models\AuditLog;
use App\Exceptions\HandleException;
use Illuminate\Http\Request;

class AuditLogController {

    public function index(Request $request) {
        try {
            $auditLogs = new AuditLog($request->route('tenant') ?? 'core');
            $logs = $auditLogs->getLogs();

            if (!$logs) {
                throw new HandleException('Logs not found', 404);
            }

            usort($logs, function ($a, $b) {
                return strtotime($b['timestamp']) <=> strtotime($a['timestamp']);
            });

            $perPage = (int) $request->query('perPage', 100);
            $currentPage = (int) $request->query('page', 1);
            $totalLogs = count($logs);
            $totalPages = ceil($totalLogs / $perPage);

            $paginatedLogs = array_slice($logs, ($currentPage - 1) * $perPage, $perPage);

            $queryParams = $request->query();
            $baseUrl = $request->url();

            $links = [
                'next' => $currentPage < $totalPages
                    ? $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $currentPage + 1]))
                    : null,
                'prev' => $currentPage > 1
                    ? $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $currentPage - 1]))
                    : null,
            ];

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'paginate' => [
                    'currentPage' => $currentPage,
                    'perPage' => $perPage,
                    'totalLogs' => $totalLogs,
                    'totalPages' => $totalPages,
                    'links' => $links,
                ],
                'payload' => $paginatedLogs,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}
