<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class ApiDashboardController extends Controller
{
    /**
     * Get chart data
     */
    public function charts(): JsonResponse
    {
        try {
            // Load JSON chart configs from resources/json/charts folder
            $lineChartConfig = $this->loadChartConfig(resource_path('json/charts/line-chart.json'));
            $barChartConfig = $this->loadChartConfig(resource_path('json/charts/bar-chart.json'));
            $pieChartConfig = $this->loadChartConfig(resource_path('json/charts/pie-chart.json'));

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

            // Statistics
            $stats = [
                'total_users' => User::count(),
                'total_flights' => Flight::count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'total_regular_users' => User::where('role', 'user')->count(),
            ];

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
        return [];
    }
}
