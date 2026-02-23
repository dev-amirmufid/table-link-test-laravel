<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get paginated list of users.
     */
    public function getUsers(int $perPage = 10): LengthAwarePaginator
    {
        return User::select(['id', 'name', 'email', 'role', 'last_login', 'created_at'])
            ->paginate($perPage);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): ?User
    {
        return User::select(['id', 'name', 'email', 'role', 'last_login', 'created_at'])
            ->find($id);
    }

    /**
     * Update user.
     */
    public function updateUser(User $user, array $data): User
    {
        // Check email uniqueness if email is being updated
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $emailExists = User::where('email', $data['email'])
                ->where('id', '!=', $user->id)
                ->exists();

            if ($emailExists) {
                throw new \Exception('Email already exists');
            }
        }

        $user->update($data);
        return $user;
    }

    /**
     * Delete user (soft delete).
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    /**
     * Check if user exists by ID.
     */
    public function userExists(int $id): bool
    {
        return User::where('id', $id)->exists();
    }
}
