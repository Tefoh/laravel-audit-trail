<?php

namespace Tofiq\AuditTrail\Resolvers;

use Illuminate\Support\Facades\Request;
use Tofiq\AuditTrail\Contracts\IpResolverInterface;

class IpResolver implements IpResolverInterface
{
    public function getIp(): string|null
    {
        return Request::ip() ?? null;
    }
}
