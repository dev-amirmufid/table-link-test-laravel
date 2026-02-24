<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiAuthRequest;
use App\Http\Requests\Api\ApiLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register new user
     */
    public function register(ApiAuthRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->authService->register($validated);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $result['user'],
                'token' => $result['token'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user (Token-based)
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->authService->login($validated);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    /**
     * Get current user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $this->authService->logout($user);

            // For session-based auth, logout from session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
