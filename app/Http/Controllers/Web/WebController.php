<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\DashboardService;
use App\Services\FlightService;
use Illuminate\Http\Request;

class WebController extends Controller
{
    protected UserService $userService;
    protected DashboardService $dashboardService;
    protected FlightService $flightService;

    public function __construct(
        UserService $userService,
        DashboardService $dashboardService,
        FlightService $flightService
    ) {
        $this->userService = $userService;
        $this->dashboardService = $dashboardService;
        $this->flightService = $flightService;
    }

    /**
     * Admin dashboard page.
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Dashboard data API (for charts).
     */
    public function dashboardData()
    {
        return response()->json(
            $this->dashboardService->getDashboardData()
        );
    }

    /**
     * User dashboard page.
     */
    public function userDashboard()
    {
        return view('dashboard.user');
    }

    /**
     * User management page.
     */
    public function users()
    {
        $users = $this->userService->getUsers(10);
        return view('users.index', compact('users'));
    }

    /**
     * Flight information page.
     */
    public function flights()
    {
        return view('flights.index');
    }

    /**
     * Update user (Web form).
     */
    public function updateUser(Request $request, int $id)
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
    public function deleteUser(int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
}
