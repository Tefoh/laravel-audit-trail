## Introduction

**_Laravel Audit Trail_** is a package that logs all SQL queries executed by your Laravel application, whether they originate from Eloquent Models or the Query Builder.

This package allows you to track query history effortlessly, requiring no modifications to your project beyond the initial setup. Once configured, all database interactions are automatically logged.

## Installation

To install the package, run the following command via [Composer](https://getcomposer.org/download/).

```bash
composer require tofiq/laravel-audit-trail
```

After installation, publish the package configuration using:

```bash
php artisan vendor:publish --provider=Tofiq\\AuditTrail\\AuditTrailServiceProvider
```

This will generate a configuration file (config/audit-trail.php), where you can customize the package settings, also the necessary migration.

Then if you want to use the database trailer then run this as well:

```bash
php artisan migrate
```

<hr />

## Customizations

#### Using a Custom Eloquent Model

By default, Laravel Audit Trail stores logs in a database table. If you prefer to use a custom Eloquent model for audit logs, define it in the `audit-trail.php` configuration file:

```php
<?php
return [
    ...

    'audit_model' => \App\Models\YourAuditTrailModel::class,
    ...
];
```

Ensure your custom model and migration are properly created and migrated.

#### Implementing a Custom Logging Service

If you need full control over how queries are logged (e.g., logging to a file, an external service, or a custom database structure), you can implement your own logging service.

1. Create a custom service class implementing the `AuditLoggerInterface`:

```php
<?php

namespace App\Services;

use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;

class MyLogService implements AuditLoggerInterface
{
    public function log(string|null $tableName, string $operationType, string $query, array $bindings = [], int|float $time = 0): void
    {
        \Log::info('Query Logged', [
            'table' => $tableName,
            'operation' => $operationType,
            'query' => $query,
            'bindings' => $bindings,
            'execution_time' => $time,
        ]);
    }
}
```

2. Register the custom service in a service provider:

```php
    public function boot(): void
    {
        $this->app->bind(
            \Tofiq\AuditTrail\Contracts\AuditLoggerInterface::class,
            \App\Services\MyLogService::class
        );
    }
```

With this setup, Laravel Audit Trail will now use your custom logging service instead of the default database logger.

## License

Laravel Audit Trail is open-sourced software licensed under the [MIT license](LICENSE.md).
