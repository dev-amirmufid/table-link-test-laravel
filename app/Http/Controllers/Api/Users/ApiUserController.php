<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create new user
     */
    public function store(ApiUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->userService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List users (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->query();
            $users = $this->userService->getAll($params);

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
            $user = $this->userService->getById($id);

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
            $validated = $request->validated();
            $user = $this->userService->update($id, $validated);

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
            $this->userService->delete($id);

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
