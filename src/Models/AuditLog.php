<?php

namespace Tofiq\AuditTrail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AuditLog extends Model
{
    protected $fillable = [
        'table_name',
        'operation_type',
        'query',
        'url',
        'bindings',
        'time',
        'user_agent',
        'ip_address',
        'user_id',
        'created_at',
    ];

    public function getConnectionName(): string|null
    {
        return Config::get('audit-trail.database.connection', Config::get('database.default'));
    }

    public function getTable(): string|null
    {
        return Config::get('audit-trail.database.table_name', 'audit_log');
    }
}
