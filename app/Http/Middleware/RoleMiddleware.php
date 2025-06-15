<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Log the user's role for debugging
        Log::info('RoleMiddleware: Checking user role', [
            'user_id' => $user ? $user->id : null,
            'role_name' => $user && $user->role ? $user->role->name : null,
            'allowed_roles' => $roles
        ]);

        if (!$user || !$user->role || !in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
