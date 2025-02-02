<?php

namespace Tofiq\AuditTrail;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Tofiq\AuditTrail\Contracts\AuditLoggerInterface;
use Tofiq\AuditTrail\Contracts\IpResolverInterface;
use Tofiq\AuditTrail\Contracts\UrlCommandResolverInterface;
use Tofiq\AuditTrail\Contracts\UserAgentResolverInterface;
use Tofiq\AuditTrail\Listeners\AuditQuery;
use Tofiq\AuditTrail\Listeners\UserLoggedIn;
use Tofiq\AuditTrail\Listeners\UserLoggedOut;
use Tofiq\AuditTrail\Resolvers\IpResolver;
use Tofiq\AuditTrail\Resolvers\UrlCommandResolver;
use Tofiq\AuditTrail\Resolvers\UserAgentResolver;
use Tofiq\AuditTrail\Services\AuditLoggerService;
use Tofiq\AuditTrail\Services\NullLoggerService;

class AuditTrailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IpResolverInterface::class, IpResolver::class);
        $this->app->bind(UserAgentResolverInterface::class, UserAgentResolver::class);
        $this->app->bind(UrlCommandResolverInterface::class, UrlCommandResolver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->mergeConfigFrom(__DIR__ . '/../config/audit-trail.php', 'audit-trail');

        /** @var string $guard */
        $guard = Config::get('audit-trail.user.guard', Config::get('auth.defaults.guard'));

        App::instance('audit.context', [
            'user_id' => Auth::guard($guard)->user()->id ?? null,
        ]);

        /** @var Model $modelInstance */
        $modelInstance = new (Config::get('audit-trail.audit_model'));
        $connection = $modelInstance->getConnectionName();
        $tableName = $modelInstance->getTable();

        $hasTable = true;
        if (! Schema::connection($connection)->hasTable($tableName)) {
            $hasTable = false;
        }
        $this->app->bind(
            AuditLoggerInterface::class,
            fn ($app) => $hasTable
                ? $app->make(AuditLoggerService::class)
                : $app->make(NullLoggerService::class)
        );

        Event::listen(QueryExecuted::class, AuditQuery::class);
        Event::listen(Login::class, UserLoggedIn::class);
        Event::listen(Logout::class, UserLoggedOut::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // Lumen lacks a config_path() helper, so we use base_path()
            $this->publishes([
                __DIR__ . '/../config/audit-trail.php' => $this->app->basePath('config/audit-trail.php'),
            ], 'config');

            /** @var string $tableName */
            $tableName = Config::get('audit-trail.database.table_name', 'audit_logs');
            $this->publishes([
                __DIR__ . '/../database/migrations/create_audit_logs_table.php' => $this->app->databasePath(
                    sprintf('migrations/%s_create_%s_table.php', date('Y_m_d_His'), $tableName),
                ),
            ], 'migrations');
        }
    }
}

