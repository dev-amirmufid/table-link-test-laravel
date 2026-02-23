<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebUserController extends Controller
{
    /**
     * Show create user form
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store new user
     */
    public function store(WebUserRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Hash password before creating user
            $validated['password'] = bcrypt($validated['password']);

            User::create($validated);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * List users
     */
    public function index(Request $request): View
    {
        try {
            $perPage = $request->query('per_page', 10);
            $search = $request->query('search', '');
            $sortBy = $request->query('sort_by', 'created_at');
            $sortDir = $request->query('sort_dir', 'desc');

            $query = User::query();

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Sort functionality - whitelist allowed columns
            $allowedSorts = ['name', 'email', 'role', 'created_at', 'last_login'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $users = $query->paginate($perPage)->appends($request->query());

            return view('users.index', compact('users', 'search', 'sortBy', 'sortDir'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    /**
     * Show user detail
     */
    public function show(int $id): View
    {
        try {
            $user = User::findOrFail($id);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'User not found');
        }
    }

    /**
     * Show edit user form
     */
    public function edit(int $id): View
    {
        try {
            $user = User::findOrFail($id);
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'User not found');
        }
    }

    /**
     * Update user
     */
    public function update(WebUserRequest $request, int $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            $user->update($validated);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot delete your own account');
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
