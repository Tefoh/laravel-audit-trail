<?php

namespace Tofiq\AuditTrail\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;

class FileLoggerService implements AuditLoggerInterface
{
    public function log(string|null $tableName, string $operationType, string $query, array $bindings = [], int|float $time = 0): void
    {

    }
}
