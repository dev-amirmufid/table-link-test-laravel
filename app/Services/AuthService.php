<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // Default role
        ]);
    }

    /**
     * Authenticate user and update last login.
     */
    public function login(array $credentials): ?User
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login' => now()]);
            return $user;
        }

        return null;
    }

    /**
     * Logout user (API).
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Get current authenticated user.
     */
    public function getCurrentUser($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'last_login' => $user->last_login,
            'created_at' => $user->created_at,
        ];
    }
}
