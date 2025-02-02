<?php

namespace Tofiq\AuditTrail\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;

class AuditQuery
{
    public function __construct(
        private AuditLoggerInterface $auditLogger,
    ) {
    }

    public function handle(QueryExecuted $event): void
    {
        /** @var array<int, mixed> $bindings */
        $bindings = $event->bindings;
        $this->auditLogger->log(
            $this->extractTableName($event->sql),
            $this->determineOperationType($event->sql),
            $event->sql,
            $bindings,
            $event->time,
        );
    }

    private function extractTableName(string $sql): string|null
    {
        // Handle UPDATE queries
        if (preg_match('/\bupdate\b\s+["`]?([\w\d_]+)["`]?/i', $sql, $matches)) {
            return $matches[1];
        }

        // Handle INSERT INTO and DELETE FROM queries
        if (preg_match('/\b(?:insert\s+into|delete\s+from)\b\s+["`]?([\w\d_]+)["`]?/i', $sql, $matches)) {
            return $matches[1];
        }

        // Handle SELECT queries
        if (preg_match('/\bfrom\b\s+["`]?([\w\d_]+)["`]?/i', $sql, $matches)) {
            return $matches[1];
        }

        // Optional: Handle JOIN queries
        if (preg_match('/\bjoin\b\s+["`]?([\w\d_]+)["`]?/i', $sql, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function determineOperationType(string $sql): string
    {
        if (preg_match('/\binsert\b/i', $sql))
            return 'INSERT';
        if (preg_match('/\bupdate\b/i', $sql))
            return 'UPDATE';
        if (preg_match('/\bdelete\b/i', $sql))
            return 'DELETE';
        return 'SELECT';
    }
}
