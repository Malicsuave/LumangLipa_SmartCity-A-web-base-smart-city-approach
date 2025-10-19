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

        // Only log role issues, not every successful check
        if (!$user || !$user->role || !in_array($user->role->name, $roles)) {
            Log::warning('RoleMiddleware: Access denied', [
                'user_id' => $user ? $user->id : null,
                'role_name' => $user && $user->role ? $user->role->name : null,
                'allowed_roles' => $roles,
                'route' => $request->route() ? $request->route()->getName() : null
            ]);
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
