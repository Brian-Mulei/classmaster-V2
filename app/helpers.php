<?php

use Stancl\Tenancy\Tenancy;

/**
 * Generate a URL within the current tenant context.
 *
 * path mode      → /tenant-slug/path
 * subdomain mode → /path  (subdomain already identifies tenant)
 */
function tenant_url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');

    if (! app(Tenancy::class)->initialized) {
        return $path;
    }

    if (config('tenancy.route_mode', 'path') === 'path') {
        return '/' . tenant()->id . $path;
    }

    return $path;
}
