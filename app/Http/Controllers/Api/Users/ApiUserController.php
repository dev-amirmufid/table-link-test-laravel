<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    /**
     * List users (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 10);
            $users = User::orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load users: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user detail
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update user
     */
    public function update(ApiUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }
}
