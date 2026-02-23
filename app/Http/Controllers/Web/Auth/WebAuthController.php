<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebAuthRequest;
use App\Http\Requests\Web\WebLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register new user
     */
    public function register(WebAuthRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'user',
            ]);

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Login user (Session-based)
     */
    public function login(WebLoginRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
            }

            // Update last login
            $user->update(['last_login' => now()]);

            // Login
            Auth::login($user);

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            }

            return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Login failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
