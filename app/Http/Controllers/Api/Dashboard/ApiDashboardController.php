<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ApiDashboardController extends Controller
{
    protected $userService;
    protected $flightService;

    public function __construct(UserService $userService, FlightService $flightService)
    {
        $this->userService = $userService;
        $this->flightService = $flightService;
    }

    /**
     * Get chart data
     */
    public function charts(): JsonResponse
    {
        Log::info('ApiDashboardController: charts called');

        try {
            $user = auth()->user();
            Log::info('ApiDashboardController: user', [
                'id' => $user?->id,
                'email' => $user?->email,
                'role' => $user?->role
            ]);

            // Load JSON chart configs from resources/json/charts folder
            $lineChartConfig = $this->loadChartConfig(resource_path('json/charts/line-chart.json'));
            $barChartConfig = $this->loadChartConfig(resource_path('json/charts/bar-chart.json'));
            $pieChartConfig = $this->loadChartConfig(resource_path('json/charts/pie-chart.json'));

            Log::info('ApiDashboardController: chart configs loaded', [
                'line' => !empty($lineChartConfig),
                'bar' => !empty($barChartConfig),
                'pie' => !empty($pieChartConfig),
            ]);

            // Extract data for API response (simplified structure for frontend)
            $lineChartData = [
                'type' => 'line',
                'labels' => $lineChartConfig['data']['labels'] ?? [],
                'datasets' => $lineChartConfig['data']['datasets'] ?? [],
                'options' => $lineChartConfig['options'] ?? [],
            ];

            $barChartData = [
                'type' => 'bar',
                'labels' => $barChartConfig['data']['labels'] ?? [],
                'datasets' => $barChartConfig['data']['datasets'] ?? [],
                'options' => $barChartConfig['options'] ?? [],
            ];

            $pieChartData = [
                'type' => 'pie',
                'labels' => $pieChartConfig['data']['labels'] ?? [],
                'datasets' => $pieChartConfig['data']['datasets'] ?? [],
                'options' => $pieChartConfig['options'] ?? [],
            ];

            // Statistics from services
            $userStats = $this->userService->getStats();
            $flightStats = $this->flightService->getStats();

            Log::info('ApiDashboardController: stats', [
                'users' => $userStats,
                'flights' => $flightStats,
            ]);

            $stats = [
                'total_users' => $userStats['total_users'] ?? 0,
                'total_flights' => $flightStats['total_flights'] ?? 0,
                'total_admins' => $userStats['total_admins'] ?? 0,
                'total_regular_users' => $userStats['total_regular_users'] ?? 0,
            ];

            Log::info('ApiDashboardController: returning success response');

            return response()->json([
                'success' => true,
                'charts' => [
                    'line' => $lineChartData,
                    'bar' => $barChartData,
                    'pie' => $pieChartData,
                ],
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('ApiDashboardController: error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load charts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Load chart configuration from JSON file
     */
    private function loadChartConfig(string $path): array
    {
        if (File::exists($path)) {
            $content = File::get($path);
            return json_decode($content, true) ?? [];
        }
        Log::warning('ApiDashboardController: chart config file not found', ['path' => $path]);
        return [];
    }
}
