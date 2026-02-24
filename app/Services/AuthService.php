<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Login user and generate token
     */
    public function login(array $credentials): array
    {
        Log::info('AuthService: Login attempt', ['email' => $credentials['email'] ?? 'N/A']);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('AuthService: Login failed - invalid credentials', ['email' => $credentials['email'] ?? 'N/A']);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Update last login
        $user->update(['last_login' => now()]);

        Log::info('AuthService: Login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Register new user
     */
    public function register(array $data): array
    {
        Log::info('AuthService: Registration attempt', ['email' => $data['email'] ?? 'N/A']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        Log::info('AuthService: Registration successful', ['user_id' => $user->id, 'email' => $user->email]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user (session-based)
     */
    public function logout(User $user): void
    {
        Log::info('AuthService: Logout', ['user_id' => $user->id, 'email' => $user->email]);

        // For session-based auth, we just log the user out
        // The controller handles the actual logout via Auth::logout()

        Log::info('AuthService: Logout successful', ['user_id' => $user->id]);
    }

    /**
     * Get current user
     */
    public function getCurrentUser(User $user): User
    {
        Log::debug('AuthService: getCurrentUser', ['user_id' => $user->id]);
        return $user;
    }
}
