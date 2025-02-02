<?php

namespace Tofiq\AuditTrail\Services;

use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;

class NullLoggerService implements AuditLoggerInterface
{
    public function log(string|null $tableName, string $operationType, string $query, array $bindings = [], int|float $time = 0): void
    {
    }
}
