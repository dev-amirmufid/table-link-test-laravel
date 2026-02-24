<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebUserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
        $this->userService->create($request->validated());
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * List users
     */
    public function index(): View
    {
        return view('users.index');
    }

    /**
     * Show user detail
     */
    public function show(int $id): View
    {
        return view('users.show', ['userId' => $id]);
    }

    /**
     * Show edit user form
     */
    public function edit(int $id): View
    {
        return view('users.edit', ['userId' => $id]);
    }

    /**
     * Update user
     */
    public function update(WebUserRequest $request, int $id): RedirectResponse
    {
        $this->userService->update($id, $request->validated());
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->userService->delete($id);
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
