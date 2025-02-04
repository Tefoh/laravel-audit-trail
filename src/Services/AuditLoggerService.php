<?php

namespace Tofiq\AuditTrail\Services;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;
use Tofiq\AuditTrail\Contracts\IpResolverInterface;
use Tofiq\AuditTrail\Contracts\UrlCommandResolverInterface;
use Tofiq\AuditTrail\Contracts\UserAgentResolverInterface;

class AuditLoggerService implements AuditLoggerInterface
{
    public function __construct(
        private IpResolverInterface $ipResolver,
        private UserAgentResolverInterface $userAgentResolver,
        private UrlCommandResolverInterface $urlCommandResolver,
    ) {
    }

    /**
     * @throws BindingResolutionException
     */
    public function log(string|null $tableName, string $operationType, string $query, array $bindings = [], int|float $time = 0): void
    {
        $auditTable = Config::get('audit-trail.database.table_name', 'audit_log');
        if (in_array($tableName, [$auditTable, 'migrations', null])) {
            return;
        }

        /** @var Model $modelInstance */
        $modelInstance = Config::get('audit-trail.audit_model');

        $modelInstance::query()->create([
            'table_name' => $tableName,
            'operation_type' => $operationType,
            'query' => $query,
            'url' => $this->urlCommandResolver->getUrlCommand(),
            'bindings' => $bindings === [] ? null : json_encode($bindings),
            'time' => $time,
            'user_agent' => $this->userAgentResolver->getUserAgent(),
            'ip_address' => $this->ipResolver->getIp(),
            'user_id' => optional(Container::getInstance()->make('audit.context'))['user_id'] ?? null,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
