<?php

namespace Tofiq\AuditTrail\Contracts;

interface IpResolverInterface
{
    public function getIp(): string|null;
}
