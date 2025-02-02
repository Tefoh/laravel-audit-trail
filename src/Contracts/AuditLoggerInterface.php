<?php

namespace Tofiq\AuditTrail\Contracts;

interface AuditLoggerInterface
{
    /** @param array<int, mixed> $bindings */
    public function log(string|null $tableName, string $operationType, string $query, array $bindings = [], int|float $time = 0): void;
}
