<?php

namespace Tofiq\AuditTrail\Resolvers;

use Illuminate\Support\Facades\Request;
use Tofiq\AuditTrail\Contracts\UserAgentResolverInterface;

class UserAgentResolver implements UserAgentResolverInterface
{
    public function getUserAgent(): string|null
    {
        return Request::header('User-Agent') ?? null;
    }
}
