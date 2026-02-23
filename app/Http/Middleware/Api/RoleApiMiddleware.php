<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleApiMiddleware
{
    /**
     * Handle an incoming API request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Check if user has required role
        if ($user->role !== $role) {
            // Admin has access to everything
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden. You don't have {$role} access."
                ], 403);
            }
        }

        return $next($request);
    }
}
