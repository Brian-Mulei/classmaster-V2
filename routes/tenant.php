<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|
| TENANT_ROUTE_MODE in .env controls identification strategy:
|
|   path      → /{tenant}/...   (local dev, no DNS needed)
|   subdomain → subdomain.app.com/...  (production, requires wildcard DNS)
|
| The tenant slug is the same in both cases — only the routing changes.
|--------------------------------------------------------------------------
*/

$mode = config('tenancy.route_mode', 'path');

if ($mode === 'subdomain') {
    Route::middleware(['web', InitializeTenancyBySubdomain::class])
        ->group(function () {
            require __DIR__ . '/tenant_routes.php';
        });
} else {
    Route::middleware(['web', InitializeTenancyByPath::class])
        ->prefix('/{tenant}')
        ->group(function () {
            require __DIR__ . '/tenant_routes.php';
        });
}
