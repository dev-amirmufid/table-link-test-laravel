<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebAuthRequest;
use App\Http\Requests\Web\WebLoginRequest;
use App\Services\AuthService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

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
     * Register new user (Sanctum SPA)
     */
    public function register(WebAuthRequest $request): RedirectResponse
    {
        Log::info('WebAuthController: Register attempt', ['email' => $request->validated()['email'] ?? 'N/A']);

        try {
            // Create user with hashed password
            $user = User::create([
                'name' => $request->validated()['name'],
                'email' => $request->validated()['email'],
                'password' => Hash::make($request->validated()['password']),
                'role' => 'user',
            ]);

            // Login user (Sanctum will set cookie automatically)
            Auth::login($user);

            Log::info('WebAuthController: Registration successful', ['user_id' => $user->id]);

            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            Log::error('WebAuthController: Registration exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Login user (Sanctum SPA)
     */
    public function login(WebLoginRequest $request): RedirectResponse
    {
        Log::info('WebAuthController: Login attempt', ['email' => $request->validated()['email'] ?? 'N/A']);

        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('WebAuthController: Login failed - invalid credentials');
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // Login user (Sanctum will set cookie automatically)
        Auth::login($user);

        // Update last login
        $user->update(['last_login' => now()]);

        Log::info('WebAuthController: Login successful', ['user_id' => $user->id, 'role' => $user->role]);

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Logout user (Sanctum SPA)
     */
    public function logout(Request $request): RedirectResponse
    {
        $userId = auth()->id();
        Log::info('WebAuthController: Logout', ['user_id' => $userId]);

        // Logout (Sanctum will clear cookie automatically)
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
