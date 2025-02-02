<?php

namespace Tofiq\AuditTrail\Resolvers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Tofiq\AuditTrail\Contracts\UrlCommandResolverInterface;

class UrlCommandResolver implements UrlCommandResolverInterface
{
    public function getUrlCommand(): string|null
    {
        if (App::runningInConsole()) {
            return $this->resolveCommand();
        }

        return Request::fullUrl();
    }

    public function resolveCommand(): string
    {
        $command = Request::server('argv', null);
        if (! is_array($command)) {
            return 'Command';
        }

        return implode(' ', $command);
    }
}
