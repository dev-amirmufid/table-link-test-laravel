<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get user registration data for line chart.
     */
    public function getUsersChartData(): array
    {
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Generate labels for the last 6 months
        $labels = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->format('M Y');
            $labels[] = $date;
            
            $count = $users->where('date', now()->subMonths($i)->format('Y-m-d'))->first();
            $data[] = $count ? $count->count : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                ],
            ],
        ];
    }

    /**
     * Get user roles distribution for pie chart.
     */
    public function getRolesChartData(): array
    {
        $roles = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();

        return [
            'labels' => $roles->pluck('role')->map(fn($role) => ucfirst($role)),
            'datasets' => [
                [
                    'data' => $roles->pluck('count'),
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get user activity data for bar chart.
     */
    public function getActivityChartData(): array
    {
        // Get users who logged in last week
        $activity = User::selectRaw('DAYNAME(last_login) as day, COUNT(*) as count')
            ->whereNotNull('last_login')
            ->where('last_login', '>=', now()->subWeek())
            ->groupBy('day')
            ->get();

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $data = [];

        foreach ($days as $day) {
            $count = $activity->where('day', $day == 'Mon' ? 'Monday' : ($day == 'Tue' ? 'Tuesday' : ($day == 'Wed' ? 'Wednesday' : ($day == 'Thu' ? 'Thursday' : ($day == 'Fri' ? 'Friday' : ($day == 'Sat' ? 'Saturday' : 'Sunday'))))))->first();
            $data[] = $count ? $count->count : 0;
        }

        return [
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.8)',
                ],
            ],
        ];
    }

    /**
     * Get all dashboard data.
     */
    public function getDashboardData(): array
    {
        return [
            'usersChart' => $this->getUsersChartData(),
            'rolesChart' => $this->getRolesChartData(),
            'activityChart' => $this->getActivityChartData(),
        ];
    }
}
