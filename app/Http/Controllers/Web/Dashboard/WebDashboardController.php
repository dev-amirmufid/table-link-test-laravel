<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class WebDashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(Request $request): View
    {
        // Stats
        $stats = [
            'total_users' => User::count(),
            'total_flights' => Flight::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_regular_users' => User::where('role', 'user')->count(),
        ];

        // Load chart configs from JSON files
        $lineChartConfig = $this->loadChartConfig(resource_path('json/charts/line-chart.json'));
        $barChartConfig = $this->loadChartConfig(resource_path('json/charts/bar-chart.json'));
        $pieChartConfig = $this->loadChartConfig(resource_path('json/charts/pie-chart.json'));

        // Prepare data for view (full config for Chart.js)
        $lineChartData = [
            'type' => $lineChartConfig['type'] ?? 'line',
            'data' => [
                'labels' => $lineChartConfig['data']['labels'] ?? [],
                'datasets' => $lineChartConfig['data']['datasets'] ?? [],
            ],
            'options' => $lineChartConfig['options'] ?? [],
        ];

        $barChartData = [
            'type' => $barChartConfig['type'] ?? 'bar',
            'data' => [
                'labels' => $barChartConfig['data']['labels'] ?? [],
                'datasets' => $barChartConfig['data']['datasets'] ?? [],
            ],
            'options' => $barChartConfig['options'] ?? [],
        ];

        $pieChartData = [
            'type' => $pieChartConfig['type'] ?? 'pie',
            'data' => [
                'labels' => $pieChartConfig['data']['labels'] ?? [],
                'datasets' => $pieChartConfig['data']['datasets'] ?? [],
            ],
            'options' => $pieChartConfig['options'] ?? [],
        ];

        return view('dashboard.admin', compact('stats', 'lineChartData', 'barChartData', 'pieChartData'));
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
