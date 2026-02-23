<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get data for admin dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->dashboardService->getDashboardData()
        );
    }

    /**
     * Get user registration data for line chart.
     */
    public function usersChart(): JsonResponse
    {
        return response()->json(
            $this->dashboardService->getUsersChartData()
        );
    }

    /**
     * Get user roles distribution for pie chart.
     */
    public function rolesChart(): JsonResponse
    {
        return response()->json(
            $this->dashboardService->getRolesChartData()
        );
    }

    /**
     * Get user activity data for bar chart.
     */
    public function activityChart(): JsonResponse
    {
        return response()->json(
            $this->dashboardService->getActivityChartData()
        );
    }
}
