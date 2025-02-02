<?php

namespace Tofiq\AuditTrail\Contracts;

interface UrlCommandResolverInterface
{
    public function getUrlCommand(): string|null;
}
