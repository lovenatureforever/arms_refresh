<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EarlyTenantIdentification
{
    /**
     * Handle an incoming request.
     *
     * This middleware initializes tenancy early in the request lifecycle,
     * BEFORE session middleware runs. This is necessary for database sessions
     * in a multi-tenant setup where sessions table is in the tenant database.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get central domains from config
        $centralDomains = config('tenancy.central_domains', []);

        // Get current domain (without port)
        $currentDomain = $request->getHost();

        // Skip if this is a central domain
        if (in_array($currentDomain, $centralDomains)) {
            return $next($request);
        }

        // Try to identify and initialize tenant
        if (!tenancy()->initialized) {
            try {
                $tenant = \App\Models\Central\Tenant::whereHas('domains', function ($query) use ($currentDomain) {
                    $query->where('domain', $currentDomain);
                })->first();

                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
            } catch (\Exception $e) {
                // Log but don't fail - let the regular tenant middleware handle errors
                \Log::warning('Early tenant identification failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
