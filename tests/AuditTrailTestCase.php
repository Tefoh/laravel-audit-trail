<?php

namespace Tofiq\AuditTrail\Tests;

use Carbon\Carbon;
use Orchestra\Testbench\TestCase;
use Tofiq\AuditTrail\AuditTrailServiceProvider;
use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;
use Tofiq\AuditTrail\Models\AuditLog;
use Tofiq\AuditTrail\Services\AuditLoggerService;

abstract class AuditTrailTestCase extends TestCase
{

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Database
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
            'options'  => [
                \PDO::ATTR_STRINGIFY_FETCHES => true,
            ],
        ]);

        // Audit trail config
        $app['config']->set('audit-trail.database.connection', 'testing');

        $app['config']->set('audit-trail.database.table_name', 'audit_log');

        $app['config']->set('audit-trail.user.guard', 'web');

        $app['config']->set('audit-trail.audit_model', AuditLog::class);

        $app['config']->set('audit-trail.logger', AuditLoggerService::class);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->app->bind(AuditLoggerInterface::class, AuditLoggerService::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            AuditTrailServiceProvider::class,
        ];
    }
}
