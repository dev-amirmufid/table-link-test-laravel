<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Works with both session (web) and token (API) authentication.
     *
     * @param string $role Required role (e.g., 'admin', 'user')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        Log::info('RoleMiddleware: Request received', [
            'url' => $request->url(),
            'method' => $request->method(),
            'user_id' => $request->user()?->id,
            'user_email' => $request->user()?->email,
            'session_id' => $request->session()?->getId(),
            'has_session' => $request->session()->has('login_web_'.sha1('App\Models\User')),
            'cookies' => $request->cookies->keys(),
        ]);

        $user = $request->user();

        // User not authenticated
        if (!$user) {
            Log::warning('RoleMiddleware: User not authenticated');

            // Return JSON for API requests, redirect for web
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }
            return redirect()->route('login');
        }

        Log::info('RoleMiddleware: User authenticated', [
            'user_id' => $user->id,
            'user_role' => $user->role,
        ]);

        // Check if user has required role
        if (!$this->hasRole($user, $role)) {
            // Return JSON for API requests, abort for web
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden. You don't have {$role} access."
                ], 403);
            }
            abort(403, "You don't have {$role} access.");
        }

        return $next($request);
    }

    /**
     * Check if user has the required role
     * Admin has access to all roles
     */
    private function hasRole($user, string $requiredRole): bool
    {
        // Admin has access to everything
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === $requiredRole;
    }
}
