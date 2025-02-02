<?php

use Tofiq\AuditTrail\Services\AuditLoggerService;

return [

    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'table_name' => 'audit_logs',
    ],

    'user' => [
        'guard' => 'web'
    ],

    'audit_model' => \Tofiq\AuditTrail\Models\AuditLog::class,

    'logger' => AuditLoggerService::class
];
