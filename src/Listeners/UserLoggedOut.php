<?php

namespace Tofiq\AuditTrail\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Container\Container;

class UserLoggedOut
{
    public function handle(Logout $event): void
    {
        Container::getInstance()->instance('audit.context', [
            'user_id' => null
        ]);
    }
}
