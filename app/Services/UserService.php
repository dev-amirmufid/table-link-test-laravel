<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Get all users with pagination, search, and sorting
     */
    public function getAll(array $params)
    {
        Log::info('UserService: getAll called', $params);

        $perPage = $params['per_page'] ?? 10;
        $search = $params['search'] ?? '';
        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortDir = $params['sort_dir'] ?? 'desc';

        $query = User::query();

        if ($search) {
            Log::debug("UserService: Searching users with term: {$search}");
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['name', 'email', 'role', 'created_at', 'last_login'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $result = $query->paginate($perPage)->appends($params);
        Log::debug("UserService: Found {$result->total()} users");

        return $result;
    }

    /**
     * Get user by ID
     */
    public function getById(int $id): ?User
    {
        Log::info("UserService: getById called with id: {$id}");

        $user = User::findOrFail($id);
        Log::debug("UserService: Found user: {$user->email}");

        return $user;
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        Log::info('UserService: create called', ['email' => $data['email'] ?? 'N/A']);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        Log::info("UserService: User created successfully with id: {$user->id}");

        return $user;
    }

    /**
     * Update user
     */
    public function update(int $id, array $data): User
    {
        Log::info("UserService: update called for user id: {$id}");

        $user = User::findOrFail($id);

        // If password is provided, hash it
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        Log::info("UserService: User {$id} updated successfully");

        return $user;
    }

    /**
     * Delete user (soft delete)
     */
    public function delete(int $id): void
    {
        Log::info("UserService: delete called for user id: {$id}");

        $user = User::findOrFail($id);
        $user->delete();

        Log::info("UserService: User {$id} deleted successfully");
    }

    /**
     * Get user statistics
     */
    public function getStats(): array
    {
        Log::debug('UserService: getStats called');

        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_regular_users' => User::where('role', 'user')->count(),
        ];

        Log::debug('UserService: Stats retrieved', $stats);

        return $stats;
    }
}
