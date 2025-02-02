<?php

namespace Tofiq\AuditTrail\Contracts;

interface UserAgentResolverInterface
{
    public function getUserAgent(): string|null;
}
