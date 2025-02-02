<?php

namespace Tofiq\AuditTrail\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;

class UserLoggedIn
{
    public function handle(Login $event): void
    {
        Container::getInstance()->instance('audit.context', [
            'user_id' => $event->user?->id
        ]);
    }
}
