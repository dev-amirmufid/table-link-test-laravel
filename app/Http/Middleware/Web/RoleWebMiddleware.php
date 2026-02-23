<?php

namespace App\Http\Middleware\Web;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleWebMiddleware
{
    /**
     * Handle an incoming web request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has required role
        if ($user->role !== $role) {
            // Check if user is admin
            if ($user->role !== 'admin') {
                abort(403, "You don't have {$role} access.");
            }
        }

        return $next($request);
    }
}
