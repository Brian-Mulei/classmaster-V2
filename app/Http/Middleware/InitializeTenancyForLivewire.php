<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Livewire sends AJAX updates to /livewire/update, which has no {tenant}
 * prefix and therefore never passes through InitializeTenancyByPath.
 *
 * This middleware detects Livewire requests and re-initialises tenancy by
 * reading the tenant slug from the Referer URL — the browser always sets
 * this to the page the component lives on, e.g. /greenfield/dashboard.
 *
 * Only active in path mode. In subdomain mode the session is already
 * scoped per subdomain so tenancy is initialised via the normal route.
 */
class InitializeTenancyForLivewire
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->hasHeader('X-Livewire')
            && ! tenancy()->initialized
            && config('tenancy.route_mode', 'path') === 'path'
        ) {
            $referer     = $request->header('Referer', '');
            $path        = parse_url($referer, PHP_URL_PATH) ?? '';
            $firstSegment = explode('/', ltrim($path, '/'))[0] ?? '';

            if ($firstSegment && $tenant = Tenant::find($firstSegment)) {
                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}
