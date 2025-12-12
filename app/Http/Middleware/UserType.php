<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserType
{
    /**
     * Handle an incoming request.
     * Check if user has one of the allowed COSEC roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types  Allowed types (admin, subscriber, director)
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        if (!auth()->check()) {
            abort(403, 'Authentication required.');
        }

        $user = auth()->user();

        // Map short names to full role names
        $roleMap = [
            'admin' => 'cosec_admin',
            'subscriber' => 'cosec_subscriber',
            'director' => 'cosec_director',
        ];

        // Convert types to role names
        $requiredRoles = array_map(fn($type) => $roleMap[$type] ?? $type, $types);

        // Check if user has any of the required roles
        if (!$user->hasAnyRole($requiredRoles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
