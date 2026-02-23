<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->userService->getUsers(10);

        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:admin,user',
        ]);

        $user = $this->userService->createUser($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $updatedUser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove the specified user (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $this->userService->deleteUser($user);

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Update user (Web form).
     */
    public function webUpdate(Request $request, int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,user',
        ]);

        $this->userService->updateUser($user, $validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    /**
     * Delete user (Web form) - soft delete.
     */
    public function webDestroy(int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
}
